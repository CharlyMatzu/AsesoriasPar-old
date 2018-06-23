<?php namespace App\Middleware;

use App\Auth;
use App\Exceptions\RequestException;
use App\Exceptions\UnauthorizedException;
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
     *
     * @throws UnauthorizedException
     * @throws \Exception
     */
    private function getAuthHeader($req){
        $header = $req->getHeader('Authorization');
        //Se verifica que exista header y que no este vació
        if( empty( $header ) )
            throw new UnauthorizedException("No esta autenticado");

//        $token = $header[0];

        //Se verifica que token sea valido
//        if( Auth::CheckToken() )

        return;
    }

    private function requireAuth(){

    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function requireAdmin($req, $res, $next)
    {
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
//        try{
//            $authHeader = $this->getAuthHeader( $req );
//        }catch (RequestException $e){
//            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "");
//        }

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
        $res = $next($req, $res);
        return $res;
    }

}