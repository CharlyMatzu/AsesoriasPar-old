<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Service\MailService;
use App\Service\PlanService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class MailController
{


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Send email
     */
    public function sendMail($req, $res){
        try {
            $mailServ = new MailService();
            $mail = $req->getAttribute("mail_data");
            $mailServ->sendMail($mail);
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Correo enviado");
        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}