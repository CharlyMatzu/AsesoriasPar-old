<?php namespace App\Exceptions;


use App\Utils;

class ForbiddenException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct($message, Utils::$FORBIDDEN, $details);
    }

}