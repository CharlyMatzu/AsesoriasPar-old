<?php namespace App;

use App\Exceptions\Request\InternalErrorException;
use DateTime;

class Utils
{

    //Status code for database registers
//    const HEADER_AUTH = "Authorization";
    const TIMEZONE = 'America/Phoenix';

    //-------General
    public static $STATUS_DISABLE = 0;
    public static $STATUS_NO_CONFIRM = 1;
    public static $STATUS_ENABLE = 2;

    //-------Advisories
    public static $STATUS_CANCELED = 0;
    public static $STATUS_PENDING = 1;
    public static $STATUS_ACTIVE = 2;
    //also for period
    public static $STATUS_FINALIZED = 3;

    //--------ROLES
    public static $ROLE_ADMIN = "administrator";
    public static $ROLE_MOD = "moderator";
    public static $ROLE_BASIC = "basic";


    //------------OTROS
    const EXPREG_EMAIL = "/[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}/";
    const EXPREG_PASS = "^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8}$";



    //++++++++++++++++++++++++
    // OPERATIONS
    //++++++++++++++++++++++++
    /**
     * Esta variable indica que Ocurrió un error en la operacion
     * @var int 1
     */
    public static $OPERATION_ERROR = 1;
    /**
     * Esta variable indica que no se encontraron resultados, es decir, es null
     * pero no Ocurrió un error
     * @var int 2
     */
    public static $OPERATION_EMPTY = 2;
    /**
     * Esta variable indica una operación exitosa sin retornar valores
     * @var int 3
     */
    public static $OPERATION_SUCCESS = 3;
    /**
     * Esta variable indica que se regreso un resultado
     * @var int 4
     */
    public static $OPERATION_RESULT = 4;


    /**
     * Cuando Ocurrió un error
     * @param $result int
     * @return bool
     */
    public static function isError($result){
        return ( $result === self::$OPERATION_ERROR ) ? true : false;
    }

    /**
     * Cuando el query hizo una operacion sin errores
     * @param $result int
     * @return bool
     */
    public static function isSuccess($result){
        return ( $result === self::$OPERATION_SUCCESS ) ? true : false;
    }

    /**
     * Cuando se retorna un resultado
     * @param $result int
     * @return bool
     */
    public static function isSuccessWithResult($result){
        return ( $result === self::$OPERATION_RESULT ) ? true : false;
    }

    /**
     * Cuando el valor es nulo o vacío
     * @param $result int
     * @return bool
     */
    public static function isEmpty($result){
        return ( $result === self::$OPERATION_EMPTY ) ? true : false;
    }


    //++++++++++++++++++++++++
    // API ERRORS
    //++++++++++++++++++++++++
    /**
     * Utilizado para aquellas peticiones realizadas correctamente
     * @var int 200
     */
    public static $OK = 200;
    /**
     * Utilizado para indicar que el recurso fue creado
     * EJ: se creó usuario
     * @var int 201
     */
    public static $CREATED = 201;
    /**
     * Utilizado para indicar que no se encontro informacion sin ser un error:
     * EJ: No hay usuarios registrados
     * @var int 204
     */
    public static $NO_CONTENT = 204;
    /**
     * Utilizado para peticiones incorrectas del cliente. Variables faltantes, vacias, etc.
     * @var int 400
     */
    public static $BAD_REQUEST = 400;
    /**
     * Se necesita estar autenticado
     * @var int 401
     */
    public static $UNAUTHORIZED = 401;
    /**
     * No se tienen los permisos (incluso al estar autenticado)
     * @var int 403
     */
    public static $FORBIDDEN = 403;
    /**
     * El valor buscado no existe
     * @var int 404
     */
    public static $NOT_FOUND = 404;
    /**
     * Utilizado para inidicar que hubo conflictos con ciertos valores
     * EJ: el recurso que se quiere crear ya existe
     * @var int 409
     */
    public static $CONFLICT = 409;
    /**
     * Errores internos del sistema (backend)
     * @var int 500
     */
    public static $INTERNAL_SERVER_ERROR = 500;


//    /**
//     * @param $message string Mensaje que identifica a la respuesta
//     * @param $data mixed informacion que se retorna con la respuesta
//     * @return array regresa un array asociativo
//     */
//    public static function makeArrayResponse($message, $data = null){
//        $array = [
//            "message" => $message,
//            "data" => $data
//        ];
//        return $array;
//    }

    /**
     *
     * @param $value array Arreglo que se transformara en json
     * @return string JSON del array
     */
    public static function makeJson($value){
        return json_encode($value);
    }


    /**
     * @param $res \Slim\Http\Response
     * @param $statusCode int
     * @param $message String
     * @return \Slim\Http\Response
     */
    public static function makeMessageResponse($res, $statusCode, $message)
    {
//        return $res->withStatus($statusCode)->withJson( ["message" => $message] );
        return $res->withStatus($statusCode)->write( $message );
    }

    /**
     * @param $res \Slim\Http\Response
     * @param $statusCode int
     * @param $data array|mixed
     * @return mixed
     */
    public static function makeResultJSONResponse($res, $statusCode, $data = null)
    {
        return $res->withStatus($statusCode)->withJson( $data );
    }

/*
//        return $res
//            ->withAddedHeader('Access-Control-Allow-Origin', '*')
//            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
//
//            ->withStatus($statusCode)->withJson(
//                self::makeArrayResponse(
//                    $message,
//                    $data
//                ));
*/

    /**
     * @param $req \Slim\Http\Request
     * @param $params mixed
     * @return array
     */
    public static function makeParamValidationArray($req, $params)
    {
        $array = [
            "route" => $req->getUri()->getPath(),
            "method" => $req->getMethod(),
            "param" => $params
        ];
        return $array;
    }

    /**
     * @param $role string
     *
     * @return bool
     */
    public static function isRole($role)
    {
        if( $role === self::$ROLE_ADMIN || $role === self::$ROLE_MOD || $role === self::$ROLE_BASIC )
            return true;
        else
            return false;
    }



    /**
     * @param $date String
     * @return bool
     */
    public static function validateDateTime($date){
        $format = 'Y/m/d';
//        $format = 'd/m/Y';
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }


    //------------------------------
    //    JSONs de configuración
    //------------------------------

    /**
     * @throws InternalErrorException
     * @return mixed JSON
     */
    public static function getMySQLConfigJSON(){
        //Obteniendo JSON
        $path_config = file_get_contents(CONFIG_PATH ."/connection.config.json");
        if( !$path_config )
            throw new InternalErrorException("MySQLConfig", "No se encontro archivo de configuracion");

        $json_config = json_decode( $path_config );
        if( $json_config == null || $json_config == false )
            throw new InternalErrorException("MailerConfig", "Error al decodificar json");


        if( !isset($json_config->mode) || !isset($json_config->connection) )
            throw new InternalErrorException("MySQLConfig", "Faltan datos de conexión");

        //Obteniendo el modo y las conexiones
        $mode = $json_config->mode;
        $connections = $json_config->connection; //objetos de conexión

        //Se obtiene conexión especifica, si no existe, se lanza error
        if( !isset($connections->$mode) )
            throw new InternalErrorException("MySQLConfig", "Faltan datos de conexión");

        $con = $connections->$mode;

        if( !isset($con->host) || !isset($con->user) || !isset($con->pass) || !isset($con->db) )
            throw new InternalErrorException("MySQLConfig", "Faltan datos de conexión");

        return $con;
    }


    /**
     * @throws InternalErrorException
     * @return mixed JSON
     */
    public static function getMailerConfigJSON(){
        //Obteniendo JSON
        $path_config = file_get_contents(CONFIG_PATH ."/mailer.config.json");
        if( !$path_config )
            throw new InternalErrorException("MailerConfig", "No se encontro archivo de configuracion");

        $json_config = json_decode( $path_config );
        if( $json_config == null || $json_config == false )
            throw new InternalErrorException("MailerConfig", "Error al decodificar json");


        if( !isset($json_config->mode) || !isset($json_config->connection) )
            throw new InternalErrorException("MailerConfig", "Faltan datos de conexión");

        //Obteniendo el modo y las conexiones
        $mode = $json_config->mode;
        $connections = $json_config->connection; //objetos de conexión

        //Se obtiene conexión especifica, si no existe, se lanza error
        if( !isset($connections->$mode) )
            throw new InternalErrorException("MailerConfig", "Faltan datos de conexión");

        $con = $connections->$mode;

        if( !isset($con->host) || !isset($con->user) || !isset($con->pass) || !isset($con->smtp_secure) || !isset($con->port) || !isset($con->name) )
            throw new InternalErrorException("MailerConfig", "Faltan datos de conexión");

        return $con;
    }

}