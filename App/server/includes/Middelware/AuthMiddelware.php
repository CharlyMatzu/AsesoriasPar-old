<?php namespace Middelware;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddelware extends Middelware
{

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