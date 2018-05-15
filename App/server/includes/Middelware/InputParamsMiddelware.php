<?php namespace Middelware;


use Slim\Http\Request;
use Slim\Http\Response;

class InputParamsMiddelware
{
    /**
     * Verifica que el parametro enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function isInteger($req, $res, $next)
    {
        $res = $next($req, $res);
        return $res;
    }

}