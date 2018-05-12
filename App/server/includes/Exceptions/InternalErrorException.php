<?php namespace Exceptions;


use Utils;

class InternalErrorException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$INTERNAL_SERVER_ERROR, $extra);
    }

}