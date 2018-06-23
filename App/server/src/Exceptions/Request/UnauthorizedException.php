<?php namespace App\Exceptions\Request;


use App\Utils;

class UnauthorizedException extends RequestException
{

    /**
     * UnauthorizedException constructor.
     *
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message, Utils::$UNAUTHORIZED);
    }

}