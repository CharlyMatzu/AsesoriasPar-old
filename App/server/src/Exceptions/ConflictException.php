<?php namespace App\Exceptions;


use App\Utils;

class ConflictException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct($message, Utils::$CONFLICT, $details);
    }

}