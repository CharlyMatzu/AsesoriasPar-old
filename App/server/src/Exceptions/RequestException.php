<?php namespace App\Exceptions;

use Exception;

class RequestException extends Exception
{
    private $status_code;
    private $extra_data;

    /**
     * RequestException constructor.
     *
     * @param string $message response message
     * @param int $response_code response status code
     * @param null $detail valores extra
     */
    public function __construct($message = "", $response_code, $detail = null)
    {
        parent::__construct($message);
        $this->status_code = $response_code;
        //Si el debug
        if( DEBUG == 1 )
            $this->extra_data = $detail;
        else
            $this->extra_data = "";
    }

    /**
     * @return int status code
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @return mixed
     */
    public function getExtraData()
    {
        return $this->extra_data;
    }

}