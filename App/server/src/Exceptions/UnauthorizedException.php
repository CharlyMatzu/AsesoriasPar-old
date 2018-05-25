<?php namespace App\Exceptions;


use App\Utils;

class UnauthorizedException extends RequestException
{
    public function __construct($message = "")
    {
        parent::__construct($message, Utils::$UNAUTHORIZED);
    }

}