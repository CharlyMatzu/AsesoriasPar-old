<?php namespace App\Exceptions;


use App\Utils;

class InternalErrorException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$INTERNAL_SERVER_ERROR, $extra);
    }

}