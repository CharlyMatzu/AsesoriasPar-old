<?php namespace App\Exceptions;

use App\Utils;

class BadRequestException extends RequestException
{
    public function __construct($message = "")
    {
        parent::__construct($message, Utils::$BAD_REQUEST);
    }
}