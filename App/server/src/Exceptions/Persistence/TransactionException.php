<?php namespace App\Exceptions\Persistence;


class TransactionException extends \Exception
{
    /**
     * TokenException constructor.
     *
     * @param $message String
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}