<?php namespace Middelware;


use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

class InputParamsMiddelware extends Middelware
{
    /**
     * Verifica que el parametro enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkId($req, $res, $next)
    {
        $id = $this->getRouteParams($req)['id'];
        if( !is_int($id) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

        $res = $next($req, $res);
        return $res;
    }

}