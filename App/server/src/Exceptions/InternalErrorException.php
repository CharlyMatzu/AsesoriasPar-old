<?php namespace App\Exceptions;


use App\AppLogger;
use App\Utils;
use Monolog\Logger;

class InternalErrorException extends RequestException
{
    /**
     * InternalErrorException constructor.
     * @param $title String titulo el evento
     * @param string $message información que describa el error
     * @param null $details detalles del error
     */
    public function __construct($title, $message = "", $details = null)
    {
        parent::__construct("Algo salio mal, intentelo más tarde", Utils::$INTERNAL_SERVER_ERROR);

        //---Log de errores
        //TODO: cada vez que ocurra un error, se debe enviar correo al admin (nosotros)
        AppLogger::makeErrorLog($title, $this->getFile()."[".$this->getLine()."] --- $message: $details");
    }

}