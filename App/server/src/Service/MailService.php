<?php namespace App\Service;
use App\Exceptions\InternalErrorException;
use App\Mailer;
use App\Model\MailModel;

class MailService
{

    /**
     * @param $mail MailModel
     *
     * @throws InternalErrorException
     */
    public function sendMail($mail){
        Mailer::sendMail($mail->getAddress(), $mail->getSubject(), $mail->getBody(), $mail->getPlainBody());
    }

}