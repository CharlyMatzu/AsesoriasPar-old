<?php namespace App\Exceptions;

use Exception;

class RequestException extends Exception
{
    private $status_code;

    /**
     * RequestException constructor.
     *
     * @param string $message response message
     * @param int $response_code response status code
     */
    public function __construct($message = "", $response_code)
    {
        parent::__construct($message);
        $this->status_code = $response_code;
    }

    /**
     * @return int status code
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }


}