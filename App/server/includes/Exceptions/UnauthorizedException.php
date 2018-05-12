<?php namespace Exceptions;


use Utils;

class UnauthorizedException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$UNAUTHORIZED, $extra);
    }

}