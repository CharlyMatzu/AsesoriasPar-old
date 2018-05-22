<?php namespace App\Exceptions;

use App\Utils;

class NotFoundException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct($message, Utils::$NOT_FOUND, $details);
    }

}