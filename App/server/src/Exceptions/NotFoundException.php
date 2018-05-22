<?php namespace App\Exceptions;

use App\Utils;

class NotFoundException extends RequestException
{
    public function __construct($message = "")
    {
        parent::__construct($message, Utils::$NOT_FOUND);
    }

}