<?php namespace App;

use App\Exceptions\InternalErrorException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    /**
     * @param $mails array email a enviar el correo
     * @param $subject String Asunto
     * @param $body String Mensjae
     * @param $plainBody
     *
     * @throws InternalErrorException
     */
    public static function sendMail($mails, $subject, $body, $plainBody){
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.ronintopics.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'asesoriaspar@ronintopics.com';                 // SMTP username
            $mail->Password = 'asesorias.123.par';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 26;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('asesoriaspar@ronintopics.com', 'AsesoriasPar');
//            $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
//            $mail->addAddress('asesoriaspar@ronintopics.com', 'AsesoriasPar');               // Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('juan_elchapo19@hotmail.com');
//            $mail->addCC('carlosrozuma@gmail.com');
//            $mail->addCC('Jrobertho-96@hotmail.com');
//            $mail->addBCC('bcc@example.com');

            //Agregando destinos
            foreach( $mails as $m ){
                $mail->addCC( $m );
            }

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $plainBody;

            $mail->send();
            AppLogger::makeActivityLog("SendMail", "Se ha enviado correo con exito");
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            throw new InternalErrorException( "SendMail", "Error al enviar correo", $mail->ErrorInfo );
        }
    }

}