<?php namespace Exceptions;


use Utils;

class ConflictException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$CONFLICT, $extra);
    }

}