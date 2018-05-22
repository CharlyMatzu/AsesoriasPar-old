<?php namespace Exceptions;

use Exception;

class RequestException extends Exception
{
    private $status_code;
    private $extra_data;

    /**
     * RequestException constructor.
     * @param string $message response message
     * @param int $response_code response status code
     * @param null $extra valores extra
     */
    public function __construct(string $message = "", int $response_code, $extra = null)
    {
        parent::__construct($message);
        $this->status_code = $response_code;
        $this->extra_data = $extra;
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