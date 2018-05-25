<?php namespace App\Middelware;


use Slim\Http\Request;

abstract class Middelware
{
    /**
     * @param $req Request
     * @return array Route Params
     */
    public function getRouteParams($req){
        return $params = $req->getAttributes()['routeInfo'][2];
    }


}