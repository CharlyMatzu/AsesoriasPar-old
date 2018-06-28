<?php namespace App\Service;

use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use App\Persistence\UsersPersistence;
use PHPMailer\PHPMailer\Exception;
use Slim\Http\Request;
use Carbon\Carbon;
use App\Utils;
use UnexpectedValueException;

class AuthService
{
    private $userPer;
    private static $secret_key = 'Sdw1s9x8@';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public function __construct(){
        $this->userPer = new UsersPersistence();
    }


    /**
     * @param $email string
     * @param $pass string
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     * TODO: solo debe funcionar si usuario esta activo
     */
    public function signIn($email, $pass){
        $result = $this->userPer->getUser_BySignIn($email, $pass);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class."signIn","Ocurrio un error al authenticar", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("email o contraseña incorrectos");

        //Si esta sin confirmar
        $user = $result->getData()[0];
        if( $user['status'] == Utils::$STATUS_NO_CONFIRM ) {
            throw new ConflictException("Usuario no ha confirmado correo electrónico");
            //TODO reenviar correo de confirmacion
        }

        //TODO: no usar id de BD
        $token = AuthService::getToken( $user['id'] );

        return [
            "user" => $user,
            "token" => $token,
        ];

    }



    



    /**
     * @param $role_user
     * @param $role_required
     * @return bool
     */
//    private function isAuthorized($role_user, $role_required)
//    {
//        //Se le podrian definir los permisos en la bd a un rol o usuario
//
//        //Si es admin, tiene permisos
//        if( $role_user ===  Utils::$ROLE_ADMIN )
//            return true;
//        //Si se requiere moderador
//        else if( $role_required === Utils::$ROLE_MOD ){
//            if( $role_user ===  Utils::$ROLE_MOD )
//                return true;
//            else
//                return false;
//        }
//        else if( $role_required === Utils::$ROLE_BASIC ){
//            if( $role_user ===  Utils::$ROLE_MOD ||
//                $role_user ===  Utils::$ROLE_BASIC)
//                return true;
//        }
//
//        return false;
//    }

//    /**
//     * @param $request Request
//     * @param $role_required int Rol requerido
//     * @return int  id del usuario asociado al token
//     * @throws UnauthorizedException    No esta autorizado para la accion
//     * @throws InternalErrorException
//     * @throws ForbiddenException
//     */
//    private function authorize($request, $role_required)
//    {
//        //Se verifica header
//        if( !$request->hasHeader( "Authorization" ) )
//            throw new UnauthorizedException();
//
//        //Se obtiene token
//        $token_auth = $request->getHeader("Authorization");
//        if( empty($token_auth) )
//            throw new UnauthorizedException();
//
//
//        //TODO verificar sin Bearer
//        //Obtiene token de string (array) sepaando de Bearer
//        $token_auth = explode(" ", $token_auth)[1];
//
//        //Verifica datos
//        try{
//            //Verifica si token es valido
//            //TODO verificar que el token sea valido
//            self::CheckToken($token_auth);
//            //Obteniendo datos del token
//            //NOTA: Hace lo mismo que el anterior pero sin verificar
//            //TODO dejar un solo paso
//            $data = self::GetData($token_auth);
//        }
//        catch (Exception $ex){
//            throw new UnauthorizedException($ex->getMessage());
//        }
//
//        $perUsers = new UsersPersistence();
//        $result = $perUsers->getUserByTokenAuth( $data );
//
//        //TODO: verificar role
//        if( Utils::isEmpty( $result->getOperation() ) )
//            throw new UnauthorizedException();
//        else if( Utils::isError( Utils::isError( $result->getOperation() ) ) )
//            throw new InternalErrorException(static::class.":authorize", "Ocurrio un error al verificar usuario", $result->getErrorMessage());
//
//        //Obtiene el primer registro
//        $user = UserService::makeUserModel($result->getData());
//        if( !self::isAuthorized( $user->getRole(), $role_required ) )
//            throw new ForbiddenException("No esta autorizado");
//
//        return $user->getId();
//    }


    //https://jwt.io/
    /**
     * Regresa el token correspondiente
     * @param $data @mixed Informacion correspondiente
     * @return string token
     */
    private static function getToken($data)
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

    /**
     * AuthService constructor.
     */

}