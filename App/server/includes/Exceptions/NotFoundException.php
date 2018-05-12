<?php namespace Exceptions;

use Utils;

class NotFoundException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$NOT_FOUND, $extra);
    }

}