<?php namespace Persistence;

use Objects\DataResult;
use Persistence\Database\MySQLConexion;
use Utils;
//use Exceptions\PersistenciaException;


abstract class Persistence{

    private static $TRANSACTION_NONE = 0;
    private static $TRANSACTION_INIT = 1;
    private static $TRANSACTION_PROGRESS = 2;
    private static $TRANSACTION_COMMIT = 3;
    private static $TRANSACTION_ROLLBACK = 4;


    /**
     * @var MySQLConexion variable de conexion inicialmente en null
     */
    private static $mysql = null;
    private static $transactionON = false;
    private static $connectionState = false;


    /**
     * Método que permite la ejecución de un Query de MySQL
     * @param String $query
     * @return DataResult objeto de resultado
     * TRUE al realizarse una operación correcta de consultas como INSERT, UPDATE o DELETE
     * FALSE al ocurrir algun error
     * Array cuando se hace una consulta de tipo SELECT y se encuentran valores
     * NULL al no encontrarse valores en una consulta SELECT (array vacio)
     */
    protected static function executeQuery($query){

        //Si no se ha creado una conexion se crea una nueva conexion
        if( !self::isConnectionON() )
            self::newConnection();

        //Ejecución del query
        $result = self::$mysql->doQuery($query);
        $response = null;


        // FALSE en caso de falló
        if( $result === false )
            $response = new DataResult(Utils::$OPERATION_ERROR, self::$mysql->getError());
        // TRUE en caso de exito (INSERT, UPDATE o DELETE)
        else if( $result === true )
            $response = new DataResult(Utils::$OPERATION_SUCCESS);

        // Array en caso de resultados obtenidos (SELECT) (No FALSE ni TRUE)
        // Null en caso de no haber resultados
        else{
            $datos = array();
            //Obtiene cada uno de los datos y los almacena en array
            while( $dato = mysqli_fetch_assoc( $result ) ){
                $datos[] = $dato;
            }
            //Array vacio
            if( empty($datos) )
                $response = new DataResult(Utils::$OPERATION_EMPTY);
            //Si hay datos, regresa el array
            else {
                $response = new DataResult(Utils::$OPERATION_RESULT, null, $datos);
            }
        }

        //Verifica transaccion y conexion antes de cerrar
        //Si no hay transaccion activa, se cierra conexion
        if( !self::isTransactionON() ){
            if( self::isConnectionON() )
                self::closeConnection();
        }

        //Regresa resultado
        return $response;

    }



    //----------------
    //  CONEXION
    //----------------
    /**
     *
     */
    public static function newConnection(){
        self::$mysql = new MySQLConexion();
        self::$connectionState = true;
    }

    public static function closeConnection(){
        self::$mysql->closeConnection();
        self::$connectionState = false;
    }

    public static function isConnectionON(){
        if( self::$connectionState == true )
            return true;
        else
            return false;
    }

    //----------------
    //  TRANSACCIONES
    //----------------


    /**
     * @return bool
     */
    public static function initTransaction(){
        //Si conexion no esta activa
        if( !self::isConnectionON() ) {
            //Inicia nueva conexion
            self::newConnection();
        }

        //Inicia transaccion (correcto)
        if( self::$mysql->iniTransaction() ){
            self::$transactionON = self::$TRANSACTION_INIT;
            return true;
        }
        else
            return false;

    }


    public static function commitTransaction(){
        if( self::isTransactionON() ){
            //Se realiza el commit
            if( self::$mysql->doCommit() ){
                //Se cambian los estados
                self::$transactionON = self::$TRANSACTION_NONE;
                self::closeConnection();
                return true;
            }
            else
                return false;
        }
        return false;
    }

    public static function rollbackTransaction(){

        if( self::$transactionON ){
            //Se realiza el rollback
            if( self::$mysql->doRollback() ){
                //Se cambian los estados
                self::$transactionON = self::$TRANSACTION_NONE;
                self::closeConnection();
                return true;
            }
            else
                return false;
        }
        else
            return false;

    }

    public static function isTransactionON(){
        return self::$transactionON;
    }


    public static function getTransactionState(){
        return self::$transactionON;
    }





    //----------------
    //  EXTRAS
    //----------------

    /**
     * @param String $data Valor a cifrar
     * @return string Regresa un String cigrafo con md5
     */
    public static function crypt(String $data){
        return md5($data);
    }


}
