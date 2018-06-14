<?php namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{

    //TODO: que este autenticado
    //TODO: que tenga los permisos para dicha operación mediante rol
    //TODO: que no tenga los permisos de modificar otro usuario siendo basic (o mod)

    /**
     * @param $req Request
     */
    private function getAuthHeader($req){
        return;
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
     * @return Response
     */
    public function requireStaff($req, $res, $next)
    {
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