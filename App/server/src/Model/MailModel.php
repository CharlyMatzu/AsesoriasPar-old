<?php namespace App\Model;

class MailModel
{
    private $address;
    private $subject;
    private $body;
    private $plainBody;
    private $attach;

    /**
     * MailModel constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param array $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return String
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param String $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return String
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param String $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return String
     */
    public function getPlainBody()
    {
        return $this->plainBody;
    }

    /**
     * @param String $plainBody
     */
    public function setPlainBody($plainBody)
    {
        $this->plainBody = $plainBody;
    }

    /**
     * @return mixed
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * @param mixed $attach
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;
    }




}