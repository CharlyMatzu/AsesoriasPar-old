<?php
class Utils
{

    //Status code for database registers
    const HEADER_AUTH = "Authorization";
    const TIMEZONE = 'America/Phoenix';

    public static $STATUS_DELETED = 0;
    public static $STATUS_ACTIVE = 1;
    //public static $DELETE = 0;

    public static $ROLE_ADMIN = "admin";
    public static $ROLE_MOD = "moderator";
    public static $ROLE_BASIC = "basic";



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
     * Utilizado para una peticion a la cual no se tiene permisos
     * @var int 401
     */
    public static $UNAUTHORIZED = 401;
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


    /**
     * @param $message string Mensaje que identifica a la respuesta
     * @param $data mixed informacion que se retorna con la respuesta
     * @return array regresa un array asociativo
     */
    public static function makeArrayResponse($message, $data = null){
        $array = [
            "message" => $message,
            "data" => $data
        ];
        return $array;
    }

    /**
     *
     * @param $value array Arreglo que se transformara en json
     * @return string JSON del array
     */
    public static function makeJson($value){
        return json_encode($value);
    }



}