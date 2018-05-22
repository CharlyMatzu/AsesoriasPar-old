<?php namespace App\Exceptions;


use App\Utils;

class UnauthorizedException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct($message, Utils::$UNAUTHORIZED, $details);
    }

}