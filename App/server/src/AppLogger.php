<?php namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppLogger
{

    /**
     * @param $logTitle string
     * @param $message string
     */
    public static function makeErrorLog($logTitle, $message){
        $log = new Logger($logTitle);
        $log->pushHandler(new StreamHandler(LOG_PATH . '/error.log', Logger::CRITICAL));
        $log->error( $message );
    }

    /**
     * @param $logTitle string
     * @param $message string
     */
    public static function makeActivityLog($logTitle, $message){
        $log = new Logger($logTitle);
        $log->pushHandler(new StreamHandler(LOG_PATH . '/activity.log', Logger::INFO));
        $log->addInfo( $message );
    }


}