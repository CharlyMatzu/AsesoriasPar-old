<?php namespace App\Service;
use App\Exceptions\InternalErrorException;
use App\Mailer;

class MailService
{

    /**
     * Envia email
     *
     * @param $mails array Array de email
     * @param $subject String asunto
     * @param $body String mensaje con HTML
     * @param $plainBody String mensaje en texto plano
     *
     * @throws \App\Exceptions\InternalErrorException
     */
    public function sendMail($mails, $subject, $body, $plainBody){
        if( !self::validateEmails( $mails ) )
            throw new InternalErrorException(static::class.":sendMail", "Email no son validos");
        Mailer::sendMail($mails, $subject, $body, $plainBody);
    }

    /**
     * @param $emails array
     * @return bool
     */
    private function validateEmails($emails){
        //TODO: validar
        return true;
    }

}