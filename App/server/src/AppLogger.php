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
        $log = new Logger( $logTitle );
        $log->pushHandler( new StreamHandler( LOG_PATH . DS . 'error.log', Logger::CRITICAL) );
        $log->critical( $message );
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