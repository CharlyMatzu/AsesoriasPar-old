<?php

namespace App;


use App\Exceptions\ForbiddenException;
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

    private static $USER_SESSION = null;
    private static $TOKEN_SESSION = null;


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
    public static function isRoleMod($user_role){
        if( $user_role === Utils::$ROLE_MOD )
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

    /**
     * @return bool
     * @throws UnauthorizedException
     */
    public static function isStaffUser(){
        $user = self::getSessionUser();
        return self::isRoleStaff($user->getRole());
    }

    /**
     * @return bool
     * @throws UnauthorizedException
     */
    public static function isModUser(){
        $user = self::getSessionUser();
        return self::isRoleMod($user->getRole());
    }

    /**
     * @return bool
     * @throws UnauthorizedException
     */
    public static function isAdminUser(){
        $user = self::getSessionUser();
        return self::isRoleAdmin($user->getRole());
    }


    /**
     * @return bool
     * @throws UnauthorizedException
     */
    public static function isBasicUser(){
        $user = self::getSessionUser();
        return self::isRoleBasic( $user->getRole() );
    }


    /**
     * @param $user UserModel
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    public static function isAuthorized($user)
    {
        //Si es otro usuario
        if( Auth::getSessionUser()->getId() !== $user->getId() )
            throw new ForbiddenException("No tiene permitido modificar usuario");
        //Si es un usuario básico
        else if( Auth::isBasicUser() ){
            //si el usuario a modificar tiene mayor rango
            if( Auth::isRoleStaff($user->getRole()) )
                throw new ForbiddenException("No tiene permitido modificar usuario");
        }
        //Si es moderador
        else if( Auth::isModUser() ){
            //si el usuario a modificar tiene mayor rango
            if( Auth::isRoleAdmin($user->getRole()) )
                throw new ForbiddenException("No tiene permitido modificar usuario");
        }
    }


    //--------------------------------
    // AUTENTICACION
    //-------------------------------


    /**
     * @param $user UserModel
     * @param $token String
     */
    public static function setSession($user, $token){
//        session_start();
//        $_SESSION['user'] = $user;
//        $_SESSION['token'] = $token;
        self::$USER_SESSION = $user;
        self::$TOKEN_SESSION = $token;
    }

    /**
     * @return UserModel
     * @throws UnauthorizedException
     */
    public static function getSessionUser(){
//        session_start();

        if( !self::isAuthenticated() )
            throw new UnauthorizedException("No autenticado");

        return self::$USER_SESSION;
    }

    /**
     * @return bool
     */
    public static function isAuthenticated()
    {

        if( empty(self::$USER_SESSION) || empty(self::$TOKEN_SESSION) )
            return false;

        //Se verifica que token funcione
        try{
            self::CheckToken( self::$TOKEN_SESSION );
            return true;
        }catch (TokenException $e){
            return false;
        }
    }

    public static function sessionDestroy()
    {
//        session_destroy();
        self::$USER_SESSION = null;
        self::$TOKEN_SESSION = null;
    }




}