<?php namespace App\Service;
use App\Exceptions\InternalErrorException;
use App\Exceptions\RequestException;
use App\Mailer;
use App\Model\MailModel;
use App\Model\UserModel;

class MailService
{

    /**
     * @param $mailModel MailModel
     *
     * @throws InternalErrorException
     */
    public function sendMail($mailModel){
        Mailer::sendMail($mailModel->getAddress(), $mailModel->getSubject(), $mailModel->getBody(), $mailModel->getPlainBody());
    }

    /**
     * @param $subject String
     * @param $body String
     * @param $staffUsers array
     */
    public function sendEmailToStaff($subject, $body, $staffUsers){
        try{
            $mail = new MailModel();
            $mail->setSubject($subject);
            $mail->setBody($body);
            $mail->setPlainBody($body);

            foreach( $$staffUsers as $user ){
                $mail->addAdress( $user['email'] );
            }
            $this->sendMail( $mail );
        }catch (RequestException $e){}
    }


    /**
     * @param $email String
     * @throws InternalErrorException
     */
    public function sendConfirmEmail($email){
        $mail = new MailModel();
        $mail->addAdress( $email );
        $mail->setSubject("Confirmacion de correo");
        //TODO: cambiar ruta de confirmacion de email
        $mail->setBody("<h3>Asesorías par</h3> <a>Favor de verificar su correo haciendo click en el siguiente enlace: <a href='".CLIENT_URL."confirm'>Confirmar</a> </p>");
        $mail->setPlainBody("CAMBIAR MENSAJE");

        try{
            $this->sendMail( $mail );
        }catch (InternalErrorException $e){
            throw new InternalErrorException("insertUserAndStudent","Error al enviar correo de confirmacion");
        }
    }

}