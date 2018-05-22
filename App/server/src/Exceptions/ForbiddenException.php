<?php namespace App\Exceptions;


use App\Utils;

class ForbiddenException extends RequestException
{
    public function __construct(string $message = "", $extra = null)
    {
        parent::__construct($message, Utils::$FORBIDDEN, $extra);
    }

}