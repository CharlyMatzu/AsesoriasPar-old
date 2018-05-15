<?php namespace Middelware;

class AuthMiddelware
{
    public function __invoke($req, $res, $next)
    {
        return $res;
    }

}