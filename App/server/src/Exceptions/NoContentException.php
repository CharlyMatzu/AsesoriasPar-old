<?php namespace Exceptions;


use Utils;

class NoContentException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$NO_CONTENT, $extra);
    }

}