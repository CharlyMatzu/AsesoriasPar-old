<?php namespace App\Middleware;

use App\Auth;
use App\Exceptions\Auth\TokenException;
use App\Exceptions\Request\ForbiddenException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NotFoundException;
use App\Exceptions\Request\RequestException;
use App\Exceptions\Request\UnauthorizedException;
use App\Persistence\UsersPersistence;
use App\Service\UserService;
use App\Utils;
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
                throw new TokenException("Authorization header invalido");


            try{
                //Se verifica token
                Auth::CheckToken( $token );
                //Se retorna
                return $token;
            }catch (TokenException $e){
                throw new UnauthorizedException( $e->getMessage() );
            }
        }
            throw new TokenException("Authorization header invalido");

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
            //NOTA: no se usa método de service ya que tiene un método que verifica el rol
            $user = null;
            $userPer = new UsersPersistence();
            $result = $userPer->getUser_ById( $data );
            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException("getUserById","Ocurrió un error al obtener usuario", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NotFoundException("No se encontró usuario");
            else
                $user = $result->getData()[0];

            //Se almacena
            Auth::setSession( UserService::makeUserModel($user), $token );

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

            //Verifica que pertenezca al rol
            if( !Auth::isAdminUser() )
                return Utils::makeMessageResponse( $res, Utils::$FORBIDDEN, "No tiene permitido dicha acción" );

        }catch (UnauthorizedException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        Auth::sessionDestroy();
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
        try{
            //Verifica auth general
            self::requireAuth($req);

            //Verifica que pertenezca al rol
            if( !Auth::isStaffUser() )
                return Utils::makeMessageResponse( $res, Utils::$FORBIDDEN, "No tiene permitido dicha acción" );

        }catch (UnauthorizedException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        Auth::sessionDestroy();
        return $res;
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    //TODO: verificar que un usuario (que no sea admin) no pueda modificar otro que no sea él mismo
    public function requireBasic($req, $res, $next)
    {
        try{
            //Verifica auth general
            self::requireAuth($req);

            //Verifica que pertenezca al rol
            //si es basic, significa que todos pueden siempre y cuando esten autenticados
            if( !Auth::isBasicUser() && !Auth::isStaffUser() )
                return Utils::makeMessageResponse( $res, Utils::$FORBIDDEN, "No tiene permitido dicha acción" );

        }catch (UnauthorizedException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        Auth::sessionDestroy();
        return $res;
    }


    //--------------------------
    // Chequeo de autorización para modificación de registros
    //---------------------------


    /**
     * Utilizado para saber si el usuario actual puede hacer modificaciones a cierto registro
     *
     * @param $req Request
     * @param $res Response
     *
     * @param $next
     *
     * @return Response
     */
    public static function isAuthorized($req, $res, $next)
    {
        try {

            //Obteniendo usuario que se intenta modificar
            $id = self::getRouteParams($req)['id'];

            $userServ = new UserService();
            $user = $userServ->getUser_ById( $id );
            $user = UserService::makeUserModel( $user );


            //Si es otro usuario
            //TODO: si esta desactivado, no tomar en cuenta
            if (Auth::getSessionUser()->getId() !== $user->getId())
                throw new ForbiddenException("No tiene permitido modificar usuario");
            //Si es un usuario básico
            else if (Auth::isBasicUser()) {
                //si el usuario a modificar tiene mayor rango
                if (Auth::isRoleStaff($user->getRole()))
                    throw new ForbiddenException("No tiene permitido modificar usuario");
            } //Si es moderador
            else if (Auth::isModUser()) {
                //si el usuario a modificar tiene mayor rango
                if (Auth::isRoleAdmin($user->getRole()))
                    throw new ForbiddenException("No tiene permitido modificar usuario");
            }

        }catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }

        $res = $next($req, $res);
        return $res;
    }


}