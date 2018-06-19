<?php namespace App\Middleware;

use App\Auth;
use App\Exceptions\RequestException;
use App\Exceptions\TokenException;
use App\Exceptions\UnauthorizedException;
use App\Service\UserService;
use App\Utils;
use Monolog\Handler\Curl\Util;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{

    //TODO: que este autenticado
    //TODO: que tenga los permisos para dicha operación mediante rol
    //TODO: que no tenga los permisos de modificar otro usuario siendo basic (o mod)

    /**
     * @param $req Request
     * @return string[]
     */
    private function getAuthorizationHeader($req){
        $header = $req->getHeader('Authorization');
        //Se verifica que exista header y que no este vació
        if( empty( $header ) )
            return null;
        else
            return $header[0];
    }

    /**
     * @param $req Request
     *
     * @return mixed
     * @throws UnauthorizedException
     * @throws TokenException
     */
    private function getBearerToken($req){
        //Se obtiene token
        $headers = $this->getAuthorizationHeader($req);
        $token = null;
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            //Se verifica el token con una expresión regular y se separa
            //http://php.net/manual/es/function.preg-match.php
            if ( preg_match('/Bearer\s(\S+)/', $headers, $matches) ) {
                //Se obtiene segunda posición (token)
                $token =  $matches[1];
            }
            else
                throw new TokenException("Bearer token invalido");
        }
        //Si no esta vacío, se continua
        if( !empty($token) ){
            //Se verifica que token sea valido
            try{
                //Se verifica token
                Auth::CheckToken( $token );
                //Se retorna
                return $token;
            }catch (TokenException $e){
                throw new UnauthorizedException($e->getMessage());
            }
        }
        else
            throw new UnauthorizedException("falta Authorization header");


    }


    /**
     * @param $req Request
     *
     * @return void
     * @throws UnauthorizedException
     * @throws RequestException
     */
    private function requireAuth($req){
        try{
            $token = $this->getBearerToken($req);

            //Se obtiene información de usuario
            $data = Auth::getData( $token );
            //Se verifica que exista usuario
            $userServ = new UserService();
            $user = $userServ->getUser_ById( $data );
            //Se almacena
            Auth::setAuthenticated( UserService::makeUserModel($user), $token );

        }catch (UnauthorizedException $e){
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        } catch (TokenException $e) {
            throw new UnauthorizedException($e->getMessage());
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     *
     * @return Response
     */
    public function requireAdmin($req, $res, $next)
    {
        try{
            //Verifica auth general
            self::requireAuth($req);

            $user = Auth::getAuthenticated();
            //Verifica que pertenezca al rol
            if( !Auth::isRoleAdmin( $user->getRole() ) )
                return Utils::makeMessageResponse( $res, Utils::$FORBIDDEN, "No tiene permitido dicha acción" );

        }catch (UnauthorizedException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        return $res;
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     *
     * @return Response
     * @throws \Exception
     */
    public function requireStaff($req, $res, $next)
    {
        //Se verifica autenticación
//        if( !Auth::isAuthenticated() )
//            return Utils::makeMessageResponse( $res, Utils::$UNAUTHORIZED, "Se requiere autenticación" );

        //Se verifica el rol del usuario
        try{
            //Verifica auth general
            self::requireAuth($req);

            $user = Auth::getAuthenticated();
            //Verifica que pertenezca al rol
            if( !Auth::isRoleStaff( $user->getRole() ) )
                return Utils::makeMessageResponse( $res, Utils::$FORBIDDEN, "No tiene permitido dicha acción" );

        }catch (UnauthorizedException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        return $res;
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    //TODO: verifica que al que se intenta acceder, sea el mismo
    public function requireBasic($req, $res, $next)
    {
        try{
            //Verifica auth general
            self::requireAuth($req);

            $user = Auth::getAuthenticated();
            //Verifica que pertenezca al rol
            //si es basic, significa que todos pueden siempre y cuando esten autenticados
            if( !Auth::isRoleBasic( $user->getRole() ) && !Auth::isRoleStaff( $user->getRole() ) )
                return Utils::makeMessageResponse( $res, Utils::$FORBIDDEN, "No tiene permitido dicha acción" );

        }catch (UnauthorizedException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        return $res;
    }

}