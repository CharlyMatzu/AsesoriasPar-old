<?php namespace App\Exceptions;


use App\AppLogger;
use App\Utils;
use Monolog\Logger;

class InternalErrorException extends RequestException
{
    /**
     * InternalErrorException constructor.
     *
     * @param $logTitle String titulo el evento
     * @param string $message informacion que describa el error
     * @param null $details detalles del error
     */
    public function __construct($logTitle, $message = "", $details = null)
    {
        parent::__construct("$message, Consultar a un administrador para verificar", Utils::$INTERNAL_SERVER_ERROR);

        //---Log de errores
        //TODO: cada vez que ocurra un error, se debe enviar correo al admin (nosotros)
        AppLogger::makeErrorLog($logTitle, "$message --> $details", Logger::ERROR);
    }

}