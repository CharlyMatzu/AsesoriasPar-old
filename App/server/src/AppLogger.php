<?php namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppLogger
{

    /**
     * @param $logTitle string
     * @param $message string
     * @param $level int
     */
    public static function makeErrorLog($logTitle, $message, $level){
        $log = new Logger($logTitle);
        $log->pushHandler(new StreamHandler(ROOT_PATH . '/logs/error.log', $level));
        $log->error( $message );
    }


}