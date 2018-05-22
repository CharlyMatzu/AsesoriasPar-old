<?php namespace App\Exceptions;


use App\Utils;

class NoContentException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct($message, Utils::$NO_CONTENT, $details);
    }

}