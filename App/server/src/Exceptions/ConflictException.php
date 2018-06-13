<?php namespace App\Exceptions;


use App\Utils;

class ConflictException extends RequestException
{
    public function __construct($message = "")
    {
        parent::__construct($message, Utils::$CONFLICT);
    }

}