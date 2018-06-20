<?php namespace App\Exceptions\Request;

use App\Utils;

class NotFoundException extends RequestException
{
    public function __construct($message = "")
    {
        parent::__construct($message, Utils::$NOT_FOUND);
    }

}