<?php namespace App;

use App\Exceptions\RequestException;
use App\Service\MailService;
use App\Service\UserService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Carbon\Carbon;

class AppLogger
{

    /**
     * @param $logTitle string
     * @param $message string
     */
    public static function makeErrorLog($logTitle, $message){
        $log = new Logger( $logTitle );
        $log->pushHandler( new StreamHandler( LOG_PATH . DS . 'error.log', Logger::CRITICAL) );
        $log->critical( $message );

        //Envío de correo a staff
//        try{
//            //usuarios
//            $userServ = new UserService();
//            $staff = $userServ->getStaffUsers();
//            //email
//            $mailServ = new MailService();
//            $mailServ->sendEmailToStaff("Asesorías Par: Error registrado",
//                "Se registro un nuevo error a las ".Carbon::Now(Utils::TIMEZONE ), $staff);
//
//        }catch (RequestException $e){}
    }

    /**
     * @param $logTitle string
     * @param $message string
     */
    public static function makeActivityLog($logTitle, $message){
        $log = new Logger($logTitle);
        $log->pushHandler(new StreamHandler(LOG_PATH . DS . 'activity.log', Logger::INFO));
        $log->info( $message );
    }


}