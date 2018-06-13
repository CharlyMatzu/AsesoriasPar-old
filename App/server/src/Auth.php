<?php

namespace App;


use Carbon\Carbon;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

abstract class Auth
{

    private static $secret_key = 'Sdw1s9x8@';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    //https://jwt.io/
    /**
     * Regresa el token correspondiente
     * @param $data @mixed Información correspondiente
     * @return string token
     */
    public static function getToken($data)
    {
        $time_now = Carbon::now(Utils::TIMEZONE);

        //https://jwt.io/introduction/
        //TODO: improve expired time
        $payload = array(
            'iat' => $time_now->timestamp, //Cuando se creo
            'exp' => $time_now->addHour(1)->timestamp, //cuando expira (una hora extra)
            'aud' => self::Aud(), //extra validation (audience)
            //Adicional
            'data' => $data
        );

        return JWT::encode($payload, self::$secret_key);
    }

    /**
     * @param $token
     * @return void
     * @throws Exception
     */
    public static function CheckToken($token)
    {
        if(empty($token))
            throw new Exception("Token invalido");

        $payload = null;
        try{
            $payload = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            );
        }catch (ExpiredException $ex){
            throw new Exception("Token ha expirado");
        }
        catch (SignatureInvalidException $ex){
            throw new Exception("Firma de token invalida");
        }


        //Validación del sistema de seguridad extra
        if($payload->aud !== self::Aud())
            throw new Exception("Sesión de Usuario invalida");

        //return $payload;
    }

    /**
     * Se obtiene información de token
     * @param $token
     * @return mixed
     * @throws Exception
     */
    public static function GetData($token)
    {
        try{
            return JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            )->data;
        }catch (UnexpectedValueException $ex){
            throw new Exception($ex->getMessage());
        }
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }

}