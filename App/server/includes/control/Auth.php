<?php namespace Control;

use Exceptions\InternalErrorException;
use Exceptions\UnauthorizedException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Persistence\Users;
use PHPMailer\PHPMailer\Exception;
use UnexpectedValueException;
use Utils;
use Slim\Http\Request;
use Carbon\Carbon;

class Auth
{
    private static $secret_key = 'Sdw1s9x8@';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    //https://jwt.io/
    /**
     * Regresa el token correspondiente
     * @param $data @mixed Informacion correspondiente
     * @return string token
     */
    public static function getToken($data)
    {
        $time_now = Carbon::now(Utils::TIMEZONE);

        //https://jwt.io/introduction/
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
     * @return int  id del usuario asociado al token
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


        //TODO verificar sin Bearer
        //Obtiene token de string (array) sepaando de Bearer
        $token_auth = explode(" ", $token_auth)[1];

        //Verifica datos
        try{
            //Verifica si token es valido
            //TODO verificar que el token sea valido
            self::CheckToken($token_auth);
            //Obteniendo datos del token
            //NOTA: Hace lo mismo que el anterior pero sin verificar
            //TODO dejar un solo paso
            $data = self::GetData($token_auth);
        }
        catch (Exception $ex){
            throw new UnauthorizedException("Token invalido");
        }

        $perUsers = new Users();
        $result = $perUsers->getUserByTokenAuth( $data );

        //TODO: verificar role
        if( Utils::isEmpty( $result->getOperation() ) )
            throw new UnauthorizedException("No esta autorizado");
        else if( Utils::isError( Utils::isError( $result->getOperation() ) ) )
            throw new InternalErrorException("Ocurrio un error al verificar usuario");

        //Obtiene el primer registro
        return $result->getData()[0]['user_id'];
    }


    /**
     * @param $token
     * @return void
     * @throws Exception
     * @throws UnauthorizedException
     */
    private static function CheckToken($token)
    {
        if(empty($token))
            throw new Exception("Invalid token supplied.");

        $payload = null;
        try{
            $payload = JWT::decode(
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
        if($payload->aud !== self::Aud())
            throw new Exception("Invalid user logged in.");

        //return $payload;
    }

    /**
     * Se obtiene informacion de token
     * @param $token
     * @return mixed
     * @throws Exception
     */
    private static function GetData($token)
    {
        try{
            return JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            )->data;
        }catch (UnexpectedValueException $ex){
            throw new Exception("Ocurrio un error al obtener datos del token");
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