<?php namespace App\Persistence\Database;

    use Exception;
    use App\Exceptions\InternalErrorException;
    use mysqli;

    class MySQLConexion {



        private $_connection;

        /**
         * Constructor que instancia una nueva conexión a la base de persistencia
         * @throws InternalErrorException Si no se puede conectar con la base de datos, o si no se puede
         * utilizar la codificación UTF-8
         */
        public function __construct() {

            //Obteniendo JSON
            $path_config = file_get_contents(ROOT_PATH."/connection.config.json");
            $json_config = json_decode( $path_config );


            if( !isset($json_config->mode) || !isset($json_config->connection) )
                throw new InternalErrorException("Connection", "Faltan datos de conexion");

            //Obteniendo el modo y las conexiones
            $mode = $json_config->mode;
            $connections = $json_config->connection; //objetos de conexion

            //Se obtiene conexion especifica, si no existe, se lanza error
            if( !isset($connections->$mode) )
                throw new InternalErrorException("Conexion", "Faltan datos de conexion");

            $con = $connections->$mode;

            if( !isset($con->host) || !isset($con->user) || !isset($con->pass) || !isset($con->db) )
                throw new InternalErrorException("Conexion", "Faltan datos de conexion");

            //Datos de conexion
            $this->_connection = new mysqli(
                $con->host,
                $con->user,
                $con->pass,
                $con->db
            );

             //Manejo de error
            if( mysqli_connect_error() ) {
                //trigger_error("Error al tratar de conectar con MySQL: " . mysqli_connect_error(), E_USER_ERROR);
                throw new InternalErrorException("Connect", "Ocurrio un error tratar de conectar con MYSQL", mysqli_connect_error());
            }

            /* cambiar el conjunto de caracteres a utf8 para aceptar tildes y 'eñes' */
            if ( !$this->_connection->set_charset('utf8') )
                throw new InternalErrorException(static::class."UTF-8","Ocurrio un error al codificar caracteres UTF8", $this->getError());
        }


        /**
         * @param $query String a ejecutar
         * @return bool|\mysqli_result Regresa un mysqli_result en caso de ser una consulta exitosa
         * de un SELECT, TRUE cuando es diferente de un SELECT exitoso y FALSE al ocurrir un error.
         */
        public function doQuery($query){
            return $this->_connection->query($query);
        }

        /**
         * Regresa el ultimo error ocurrido
         * @return string Mensaje del error
         */
        public function getError(){
            return $this->_connection->error;
        }

        /**
         * Cierra la conexión
         * @return bool FALSE en caso de error o fallo, TRUE exitoso
         */
        public function closeConnection(){
            return $this->_connection->close();
        }


        //----------TRANSACCIONES
        /**
         * Inicio de transaccion (evita el registro automatico de datos)
         * @return bool FALSE en caso de error o fallo, TRUE exitoso
         */
        public function iniTransaction(){
            return $this->_connection->autocommit( false );
        }

        /**
         * Commit de transaccion (Registro de datos)
         * @return bool FALSE en caso de error o fallo, TRUE exitoso
         */
        public function doCommit(){
            return $this->_connection->commit();
        }

        /**
         * Retroceso en registro de datos (no registra)
         * @return bool FALSE en caso de error o fallo, TRUE exitoso
         */
        public function doRollback(){
           return $this->_connection->rollback();
        }





    }