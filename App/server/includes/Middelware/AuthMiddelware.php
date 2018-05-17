<?php namespace Middelware;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddelware extends Middelware
{

    //TODO: que este autenticado
    //TODO: que tenga los permisos para dicha operacion mediante rol
    //TODO: que no tenga los permisos de modificar otro usuario siendo basic (o mod)


    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function __invoke($req, $res, $next)
    {
        $res = $next($req, $res);
        return $res;
    }

}