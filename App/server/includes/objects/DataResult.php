<?php namespace Objects;

use mysqli_result;

class DataResult
{
    private $operation;
    private $error_message;
    private $data;

    public function __construct($operation, $error_message = null, $data = null)
    {
        $this->operation = $operation;
        $this->error_message = $error_message;
        $this->data = $data;
    }

    /**
     * @return int|bool
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param int|bool $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param null|string $error_message
     */
    public function setErrorMessage($error_message)
    {
        //TODO: verificar mensaje
        if( DEBUG == 0 )
            $error_message = "";
        $this->error_message = $error_message;
    }

    /**
     * @return null|mysqli_result
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null|mysqli_result
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}