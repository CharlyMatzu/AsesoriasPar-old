<?php namespace App\Persistence\Database;

    use App\Exceptions\Request\InternalErrorException;
    use App\Exceptions\Request\RequestException;
    use App\Utils;
    use mysqli;

    class MySQLConnection {



        private $_connection;

        /**
         * Constructor que instancia una nueva conexión a la base de persistencia
         * @throws InternalErrorException Si no se puede conectar con la base de datos, o si no se puede
         * utilizar la codificación UTF-8
         */
        public function __construct() {

            $con = null;
            try{
                $con = Utils::getMySQLConfigJSON();
            }catch (RequestException $e){
                throw new InternalErrorException("MySQL", "Error con archivo de configuración");
            }

            //Datos de conexión
            $this->_connection = new mysqli(
                $con->host,
                $con->user,
                $con->pass,
                $con->db
            );

             //Manejo de error
            if( mysqli_connect_error() ) {
                //trigger_error("Error al tratar de conectar con MySQL: " . mysqli_connect_error(), E_USER_ERROR);
                throw new InternalErrorException("Connect", "Ocurrió un error tratar de conectar con MYSQL", mysqli_connect_error());
            }

            /* cambiar el conjunto de caracteres a utf8 para aceptar tildes y 'eñes' */
            if ( !$this->_connection->set_charset('utf8') )
                throw new InternalErrorException("UTF-8","Ocurrió un error al codificar caracteres UTF8", $this->getError());
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


        //----------transacciónES
        /**
         * Inicio de transacción (evita el registro automatico de datos)
         * @return bool FALSE en caso de error o fallo, TRUE exitoso
         */
        public function iniTransaction(){
            return $this->_connection->autocommit( false );
        }

        /**
         * Commit de transacción (Registro de datos)
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