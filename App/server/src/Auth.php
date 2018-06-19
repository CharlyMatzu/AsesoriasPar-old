<?php

namespace App;


use App\Exceptions\TokenException;
use App\Exceptions\UnauthorizedException;
use App\Model\UserModel;
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


    //--------------------------------
    // JWT -> https://jwt.io/
    //-------------------------------

    /**
     * Genera un token y lo retorna
     * @param $data @mixed Información correspondiente
     * @param int $hours
     *
     * @return string token
     */
    public static function getToken($data, $hours = 24)
    {
        $time_now = Carbon::now(Utils::TIMEZONE);

        //https://jwt.io/introduction/
        //TODO: improve expired time
        $payload = array(
            'iat' => $time_now->timestamp, //Cuando se creo
            'exp' => $time_now->addHour($hours)->timestamp, //cuando expira (horas extra)
            'aud' => self::Aud(), //extra validation (audience)
            //Adicional
            'data' => $data
        );

        return JWT::encode($payload, self::$secret_key);
    }

    /**
     * Regresa el token decodificado
     * @param $token
     * @return object
     * @throws TokenException
     */
    public static function CheckToken($token)
    {
        if(empty($token))
            throw new TokenException("Token invalido");

        $payload = null;
        try{
            $payload = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            );
        }catch (ExpiredException $ex){
            throw new TokenException("Token ha expirado");
        }
        catch (SignatureInvalidException $ex){
            throw new TokenException("Firma de token invalida");
        }


        //Validación del sistema de seguridad extra
//        if($payload->aud !== self::Aud())
//            throw new Exception("Sesión de Usuario invalida");

        return $payload;
    }

    /**
     * Se obtiene información de token
     *
     * @param $token
     *
     * @return mixed
     * @throws TokenException
     */
    public static function getData($token)
    {
        try{
            return JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            )->data;
        }catch (UnexpectedValueException $ex){
            throw new TokenException( $ex->getMessage() );
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


    //--------------------------------
    // ROLES
    //-------------------------------

    /**
     * @param $user_role int
     *
     * @return bool
     */
    public static function isRoleAdmin($user_role){
        if( $user_role === Utils::$ROLE_ADMIN )
            return true;
        else
            return false;
    }

    /**
     * @param $user_role int
     *
     * @return bool
     */
    public static function isRoleStaff($user_role){
        if( $user_role === Utils::$ROLE_ADMIN || $user_role === Utils::$ROLE_MOD )
            return true;
        else
            return false;
    }

    /**
     * @param $user_role int
     *
     * @return bool
     */
    public static function isRoleBasic($user_role){
        if( $user_role === Utils::$ROLE_BASIC )
            return true;
        else
            return false;
    }


    //--------------------------------
    // AUTENTICACION
    //-------------------------------


    /**
     * @param $user UserModel
     * @param $token String
     */
    public static function setAuthenticated($user, $token){
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['token'] = $token;
    }

    /**
     * @return UserModel
     * @throws UnauthorizedException
     */
    public static function getAuthenticated(){
        session_start();

        if( !self::isAuthenticated() )
            throw new UnauthorizedException("No autenticado");

        return $_SESSION['user'];
    }

    /**
     * @return bool
     */
    public static function isAuthenticated()
    {
        if( !isset($_SESSION['user']) || !isset($_SESSION['token']) )
            return false;

        if( empty($_SESSION['user']) || empty($_SESSION['token']) )
            return false;

        //Se verifica que token funcione
        try{
            self::CheckToken( $_SESSION['token'] );
            return true;
        }catch (TokenException $e){
            return false;
        }
    }


}