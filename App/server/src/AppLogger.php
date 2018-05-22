<?php namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppLogger
{

    /**
     * @param $name string
     * @param $message string
     * @param $level int
     */
    public static function makeErrorLog($name, $message, $level){
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler(ROOT_PATH . '/logs/error.log', $level));
        $log->error( $message );
    }


}