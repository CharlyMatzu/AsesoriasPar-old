<?php namespace App\Exceptions;


use App\Utils;

class UnauthorizedException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$UNAUTHORIZED, $extra);
    }

}