<?php namespace Control;

use Exceptions\InternalErrorException;
use Exceptions\UnauthorizedException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Persistence\Users;
use PHPMailer\PHPMailer\Exception;
use Utils;
use Slim\Http\Request;

class Auth
{
    private static $secret_key = 'Sdw1s9x8@';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    /**
     * Regresa el token correspondiente
     * @param $data @mixed Informacion correspondiente
     * @return string token
     */
    public static function getToken($data)
    {
        $time = time();

        //https://jwt.io/introduction/
        $payload = array(
            'iat' => $time, //Cuando se creo
            'exp' => $time + (7*24*60*60), //cuando expira (una hora extra)
            'aud' => self::Aud(), //extra validation (audience)
            //Adicional
            'data' => $data
        );

        return JWT::encode($payload, self::$secret_key);
    }

    /**
     * @param $token String
     * @return bool
     */
    public static function isAuthenticated($token)
    {
        return true;
    }

    /**
     * @param $request Request
     * @param $role_required int Rol requerido
     * @throws UnauthorizedException    No esta autorizado para la accion
     * @throws InternalErrorException
     */
    public static function authorize($request, $role_required)
    {
        //Se verifica header
        if( !$request->hasHeader( "Authorization" ) )
            throw new UnauthorizedException("No esta autorizado");

        //Se obtiene token
        $token_auth = $request->getHeader("Authorization")[0];
        if( empty($token_auth) )
            throw new UnauthorizedException("No esta autorizado");

        //Obtiene token de string (array)
        //TODO verificar sin Bearer
        $token_auth = explode(" ", $token_auth)[1];
        //Verifica si token es valido
        try{
            $token_auth = self::CheckToken($token_auth);
        }
        catch (Exception $ex){
            throw new UnauthorizedException("No esta autorizado");
        }

        //Verifica datos
        $data_array = self::GetData($token_auth);
        $perUsers = new Users();
        $result = $perUsers->getUserByTokenAuth( $data_array['id'], $data_array['email'] );

        //TODO: verificar role
        if( Utils::isEmpty( $result->getOperation() ) )
            throw new UnauthorizedException("No esta autorizado");
        else if( Utils::isError( Utils::isError( $result->getOperation() ) ) )
            throw new InternalErrorException("Ocurrio un error al verificar usuario");

    }


    /**
     * @param $token
     * @return object
     * @throws Exception
     * @throws UnauthorizedException
     */
    private static function CheckToken($token)
    {
        if(empty($token))
            throw new Exception("Invalid token supplied.");

        $decode = null;
        try{
            $decode = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            );
        }catch (ExpiredException $ex){
            throw new UnauthorizedException($ex->getMessage());
        }
        catch (SignatureInvalidException $ex){
            throw new UnauthorizedException($ex->getMessage());
        }


        //Validacion del sistema de seguridad extra
        if($decode->aud !== self::Aud())
            throw new Exception("Invalid user logged in.");

        return $decode;
    }

    /**
     * Se obtiene informacion de token
     * @param $token
     * @return mixed
     */
    private static function GetData($token)
    {
        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
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