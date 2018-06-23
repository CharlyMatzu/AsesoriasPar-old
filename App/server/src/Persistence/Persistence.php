<?php namespace App\Persistence;

use App\Exceptions\Persistence\TransactionException;
use App\Exceptions\Request\InternalErrorException;
use App\Model\DataResult;
use App\Persistence\Database\MySQLConnection;
use App\Utils;


abstract class Persistence{

    private static $TRANSACTION_NONE = 0;
    private static $TRANSACTION_INIT = 1;
//    private static $TRANSACTION_PROGRESS = 2;
//    private static $TRANSACTION_COMMIT = 3;
//    private static $TRANSACTION_ROLLBACK = 4;


    /**
     * @var MySQLConnection variable de conexión inicialmente en null
     */
    private static $mysql = null;
    private static $transactionON = false;
    private static $connectionState = false;


    /**
     * Método que permite la ejecución de un Query de MySQL
     *
     * @param String $query
     *
     * @return DataResult objeto de resultado
     * TRUE al realizarse una operación correcta de consultas como INSERT, UPDATE o DELETE
     * FALSE al ocurrir algún error
     * Array cuando se hace una consulta de tipo SELECT y se encuentran valores
     * NULL al no encontrarse valores en una consulta SELECT (array vacío)
     * @throws InternalErrorException
     */
    protected static function executeQuery($query){

        //Si no se ha creado una conexión se crea una nueva conexión
        if( !self::isConnectionON() )
            self::newConnection();

        //Ejecución del query
        $result = self::$mysql->doQuery($query);
        $response = null;


        // FALSE en caso de falló
        if( $result === false )
            $response = new DataResult(Utils::$OPERATION_ERROR, self::$mysql->getError());
        // TRUE en caso de éxito (INSERT, UPDATE o DELETE)
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
            //Array vacío
            if( empty($datos) )
                $response = new DataResult(Utils::$OPERATION_EMPTY);
            //Si hay datos, regresa el array
            else {
                $response = new DataResult(Utils::$OPERATION_RESULT, null, $datos);
            }
        }

        //Verifica transacción y conexión antes de cerrar
        //Si no hay transacción activa, se cierra conexión
        if( !self::isTransactionON() ){
            if( self::isConnectionON() )
                self::closeConnection();
        }

        //Regresa resultado
        return $response;

    }



    //----------------
    //  conexión
    //----------------
    /**
     *
     */
    public static function newConnection(){
        self::$mysql = new MySQLConnection();
        self::$connectionState = true;
    }

    /**
     * Cierra conexión, true en caso de ser exitoso
     * @throws InternalErrorException
     */
    public static function closeConnection(){
        if( !self::$mysql->closeConnection() )
            throw new InternalErrorException("closeConnection", "Ocurrió un error al cerrar conexión");

        self::$connectionState = false;
    }

    public static function isConnectionON(){
        return self::$connectionState;
    }

    //----------------
    //  transacciónES
    //----------------


    /**
     * Se encarga de activar las transacciones de MYSQL, iniciando antes una conexión en caso de no haber
     * @throws TransactionException
     */
    public static function initTransaction(){
        //Si conexión no esta activa
        if( !self::isConnectionON() ) {
            //Inicia nueva conexión
            self::newConnection();
        }

        //Inicia transacción (correcto)
        if( self::$mysql->iniTransaction() ){
            self::$transactionON = self::$TRANSACTION_INIT;
        }
        else
            throw new TransactionException("No se pudo iniciar transacción");

    }


    /**
     * Permite registrar los cambios
     * @throws TransactionException
     * @throws InternalErrorException
     */
    public static function commitTransaction(){
        if( self::isTransactionON() ){
            //Se realiza el commit
            if( self::$mysql->doCommit() ){
                //Se cambian los estados
                self::$transactionON = self::$TRANSACTION_NONE;
                //Cierra conexión
                self::closeConnection();
            }
            else
                throw new TransactionException("No se pudo registrar transacción");
        }
        else
            throw new TransactionException("Debe iniciarse transacción antes de registrar");
    }

    /**
     * Limpiar transacción
     * @throws InternalErrorException
     * @throws TransactionException
     */
    public static function rollbackTransaction(){

        if( self::$transactionON ){

            //Se realiza el rollback
            if( self::$mysql->doRollback() ){
                //Se cambian los estados
                self::$transactionON = self::$TRANSACTION_NONE;
                self::closeConnection();
            }
            //Si no se pudo deshacer
            else
                throw new TransactionException("No se pudo deshacer registro");
        }
        else
            throw new TransactionException("Transacción debe ser iniciada antes de deshacer");

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
     * @return string Regresa un String cifrado con md5 o sha1
     */
    public static function crypt($data){
//        return sha1($data);
        return md5($data);
    }


}
