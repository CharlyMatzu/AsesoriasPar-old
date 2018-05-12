<?php namespace Exceptions;

use Utils;

class BadRequestException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$BAD_REQUEST, $extra);
    }
}