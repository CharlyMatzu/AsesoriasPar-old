<?php namespace App\Exceptions;


use App\Utils;

class InternalErrorException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct("$message, Consultar a un administrador para verificar", Utils::$INTERNAL_SERVER_ERROR);
        
    }

}