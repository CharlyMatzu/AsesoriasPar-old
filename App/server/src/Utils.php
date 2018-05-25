<?php namespace App;
class Utils
{

    //Status code for database registers
    const HEADER_AUTH = "Authorization";
    const TIMEZONE = 'America/Phoenix';

    //-------General
    public static $STATUS_DISABLE = 0;
    public static $STATUS_NO_CONFIRM = 1;
    public static $STATUS_ENABLE = 2;

    //-------Advisories
    public static $STATUS_CANCELED = 0;
    public static $STATUS_PENDING = 1;
    public static $STATUS_ACTIVE = 2;


    public static $ROLE_ADMIN = "admin";
    public static $ROLE_MOD = "moderator";
    public static $ROLE_BASIC = "basic";


    //------------OTROS
    const EXPREG_EMAIL = "/[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}/";
    const EXPREG_PASS = "^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8}$";



    //++++++++++++++++++++++++
    // OPERATIONS
    //++++++++++++++++++++++++
    /**
     * Esta variable indica que ocurrio un error en la operacion
     * @var int 1
     */
    public static $OPERATION_ERROR = 1;
    /**
     * Esta variable indica que no se encontraron resultados, es decir, es null
     * pero no ocurrio un error
     * @var int 2
     */
    public static $OPERATION_EMPTY = 2;
    /**
     * Esta variable indica una operacion exitosa sin retornar valores
     * @var int 3
     */
    public static $OPERATION_SUCCESS = 3;
    /**
     * Esta variable indica que se regreso un resultado
     * @var int 4
     */
    public static $OPERATION_RESULT = 4;


    /**
     * Cuando ocurrio un error
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
     * Cuando el valor es nulo o vacio
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
    public static function makeMessageJSONResponse($res, $statusCode, $message)
    {
        return $res->withStatus($statusCode)->withJson( ["message" => $message] );
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

    public static function isRole($role)
    {
        if( $role === self::$ROLE_ADMIN || $role === self::$ROLE_MOD || $role === self::$ROLE_BASIC )
            return true;
        else
            return false;
    }

}