<?php namespace App\Exceptions;


use App\AppLogger;
use App\Utils;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class InternalErrorException extends RequestException
{
    public function __construct($message = "", $details = null)
    {
        parent::__construct("$message, Consultar a un administrador para verificar", Utils::$INTERNAL_SERVER_ERROR);

        //---Log de errores
        //TODO: cada vez que ocurra un error, se debe enviar correo al admin (nosotros)
        AppLogger::makeErrorLog("InternalError:", "$message: $details", Logger::ERROR);
    }

}