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
     * @param $role_user
     * @param $role_required
     * @return bool
     */
    public static function isAuthorized($role_user, $role_required)
    {
        //Se le podrian definir los permisos en la bd a un rol o usuario

        //Si es admin, tiene permisos
        if( $role_user ===  Utils::$ROLE_ADMIN )
            return true;
        //Si se requiere moderador
        else if( $role_required === Utils::$ROLE_MOD ){
            if( $role_user ===  Utils::$ROLE_MOD )
                return true;
            else
                return false;
        }
        else if( $role_required === Utils::$ROLE_BASIC ){
            if( $role_user ===  Utils::$ROLE_MOD ||
                $role_user ===  Utils::$ROLE_BASIC)
                return true;
        }

        return false;
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
            throw new UnauthorizedException("Error: ".$ex->getMessage());
        }

        $perUsers = new Users();
        $result = $perUsers->getUserByTokenAuth( $data );

        //TODO: verificar role
        if( Utils::isEmpty( $result->getOperation() ) )
            throw new UnauthorizedException("No esta autorizado");
        else if( Utils::isError( Utils::isError( $result->getOperation() ) ) )
            throw new InternalErrorException("Ocurrio un error al verificar usuario");

        //Obtiene el primer registro
        $user = UserControl::makeObject_User($result->getData()[0]);
        if( !self::isAuthorized( $user->getRole(), $role_required ) )
            throw new UnauthorizedException("No esta autorizado");

        return $user->getId();
    }


    /**
     * @param $token
     * @return void
     * @throws Exception
     */
    private static function CheckToken($token)
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


        //Validacion del sistema de seguridad extra
        if($payload->aud !== self::Aud())
            throw new Exception("Sesion de Usuario invalida");

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