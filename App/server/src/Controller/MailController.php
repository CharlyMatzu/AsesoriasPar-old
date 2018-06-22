<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
use App\Service\MailService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class MailController
{


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function sendMail($req, $res){
        try {
            $mailServ = new MailService();
            $mail = $req->getAttribute("mail_data");
            $mailServ->sendMail($mail);
            return Utils::makeMessageResponse( $res, Utils::$OK, "Correo enviado");
        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}