<?php namespace App;

use App\Exceptions\InternalErrorException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    /**
     * @param $mails array email a enviar el correo
     * @param $subject String Asunto
     * @param $body String Mensaje
     * @param $plainBody
     *
     * @throws InternalErrorException
     */
    public static function sendMail($mails, $subject, $body, $plainBody)
    {
        $con = null;
        try {
            $con = Utils::getMailerConfigJSON();
        } catch (InternalErrorException $e) {
            throw new InternalErrorException("Mailer", "Ocurrió un error con archivo de configuración");
        }


        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $con->host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $con->user;                 // SMTP username
            $mail->Password = $con->pass;                           // SMTP password
            $mail->SMTPSecure = $con->smtp_secure;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $con->port;                                    // TCP port to connect to
            $mail->CharSet = 'UTF-8';  //caracteres especiales

            //Recipients
            $mail->setFrom($con->user, $con->name);
//            $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
//            $mail->addAddress('asesoriaspar@ronintopics.com', 'asesoriasPar');               // Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('juan_elchapo19@hotmail.com');
//            $mail->addCC('carlosrozuma@gmail.com');
//            $mail->addCC('Jrobertho-96@hotmail.com');
//            $mail->addBCC('bcc@example.com');

            //Agregando destinos
            foreach ($mails as $m) {
                $mail->addCC($m);
            }

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $plainBody;
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->send();
            AppLogger::makeActivityLog("Mailer", "Se ha enviado correo con exito");
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            throw new InternalErrorException("Mailer", "Error al enviar correo", $mail->ErrorInfo);
        }
    }

}