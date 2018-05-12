<?php namespace Control;

use Exceptions\BadRequestException;
use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Persistence\Users;
use Objects\Student;
use Objects\User;
use Utils;

class UserControl{

    private $perUsers;

    public function __construct(){
        $this->perUsers = new Users();
    }


    /**
     * Obtiene todos los usuarios registrados
     * @return array|bool|null
     * @throws InternalErrorException Si no se pudo obtener a los usuarios
     * @throws NoContentException Si no se encuentra ningun usuario
     */
    public function getUsers(){
        $result = $this->perUsers->getUsers();
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result == Utils::$OPERATION_RESULT)
            return $result;
        else
            return $result;
    }


    /**
     * @param $user
     * @param $pass
     * @return array|bool|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getUser_ByAuth($user, $pass){
        $result = $this->perUsers->getUser_ByAuth($user, $pass);
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    /**
     * @param $id
     * @return array|bool|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getUser_ById($id){
        $result = $this->perUsers->getUser_ById( $id );
        if($result['operation'] == Utils::$OPERATION_RESULT )
            return $result;
        else if( $result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else
            return $result;
    }


    /**
     * @param $id int
     * @return bool TRUE si existe, FALSE si no existe
     * @throws InternalErrorException si ocurrio un error
     * @throws NotFoundException si no existe el
     */
    public function isUserExist_ById($id){
        $result = $this->getUser_ById($id);
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if($result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getRoleUser($id){
        $result = $this->perUsers->getRoleUser( $id );
        if( $result['operation'] === Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    public function ifExistRole($role){
        $result = $this->perUsers->ifExistRole( $role );
        if( $result['operation'] === Utils::$OPERATION_RESULT )
            return true;
        else if( $result['operation'] === Utils::$OPERATION_EMPTY)
            return false;
        else
            return $result;
    }


    public function getUser_ByEmail($email){
        $result = $this->perUsers->getUser_ByEmail( $email );
        if( $result['operation'] === Utils::$OPERATION_SUCCESS)
            return true;
        else if( $result['operation'] === Utils::$OPERATION_EMPTY)
            return false;
        else
            return $result;
    }

    /**
     * @param $email String
     */
    public function isUserExist_ByEmail($email){
        $result = $this->getUser_ByEmail($email);
        if( $result['operation'] === Utils::$OPERATION_RESULT)
            return true;
        else if( $result['operation'] === Utils::$OPERATION_EMPTY)
            return false;
        else
            return $result;
    }

    //------------------REGISTRAR USUARIO


    /**
     * @param $user User
     * @param $student Student
     * @return array
     * @throws BadRequestException
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertUser($user){
        $userId = $user->getId();

        if( $userId != null ) {
            $result = $this->isUserExist_ById($userId);

            if ($result == false)
                throw new ConflictException("Usuario no existe");
            else if ($result == true) {
                $result = $this->getRoleUser($userId);
                if ($result == false)
                    throw new ConflictException("Usuario no tiene rol de estudiante o esta inhabil");
                else if ($result == true) {
                    return $result;
                } else {
                    throw new InternalErrorException("Ocurrio un error al buscar rol", $result['error']);
                }
            }else{
                throw new InternalErrorException("Ocurrio un error al buscar usuario por ID", $result['error']);
            }
        }else {
            //Insercion de usuario

            $result = $this->isUserExist_ByEmail( $user->getEmail() );
            if ($result == true)
                throw new ConflictException("Email ya existe");
            else if ($result == false) {
                $result = $this->ifExistRole( $user->getRole() );
                if ($result == false)
                    throw new ConflictException("Rol no existe");
            }else{
                throw new ConflictException("Ocurrio un error al veirifcar email");
            }

            $result = $this->perUsers->insertUser( $user );
            if( $result['operation'] == Utils::$OPERATION_ERROR )
                throw new InternalErrorException("Ocurrio un error al registrar usuario", $result['error']);
            else
                return Utils::makeArrayResponse(
                    "Se registro usuario con éxito",
                    'Correcto!'
                );
        }
    }

    /**
     * @param $user User
     * @param $student Student
     * @return array
     */
    public function updateUser($user){
        $result = $this->getUser_ById( $user->getId() );

        if ($result == false)
            throw new ConflictException("Usuario no existe");
        else if ($result == true){
            $result = $this->isUserExist_ByEmail( $user->getEmail() );
            if ($result == true)
                throw new ConflictException("Email ya existe");
            else if ($result == false) {
                $result = $this->ifExistRole( $user->getRole() );
                if ($result == false)
                    throw new ConflictException("Rol no existe");
            }else{
                throw new ConflictException("Ocurrio un error al veirifcar email");
            }
        }else{
            throw new ConflictException("Ocurrio un error al veirifcar el ID");
        }

        $result = $this->perUsers->updateUser( $user );
        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al actualizar usuario", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Se actualizo usuario con éxito",
                'Correcto!'
            );
    }

    /**
     * @param $id int
     * @param $student Student
     * @return array
     */
    public function deleteUser( $id ){
        $result = $this->getUser_ById( $id );

        if ($result == false)
            throw new ConflictException("Usuario no existe");

        $result = $this->perUsers->deleteUser( $id );
        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al eliminar el usuario", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Se elimino el usuario con éxito",
                'Correcto!'
            );

    }


    /**
     * @param $user User
     * @param $student Student
     * @return array
     */
    public function searchUser($search_by, $data_search)
    {
        if ($search_by === "id") {
            $result = $this->getUser_ById($data_search);
            if ($result == false)
                throw new ConflictException("Usuario no existe por id");
        } else if ($search_by === "email") {
            $result = $this->getUser_ByEmail($data_search);
            if ($result == false)
                throw new ConflictException("Usuario no existe por email");
        } else if($search_by === "users"){
            $result = $this->getUsers();
            if ($result == false)
                throw new ConflictException("Usuarios no existen");
        } else{
            $response = [
                "result" => "Error en los datos",
                "message" => "Verifique los datos"
            ];
            return $response;
        }

        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al registrar usuario", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Busqueda exitosa",
                $result
            );
    }









        //------------------REGISTRAR USUARIO Y ESTUDIANTE

    /**
     * @param $user User
     * @param $student Student
     * @return array
     */
    public function insertUserAndStudent($user, $student){
        $trans = Users::initTransaction();
        //TODO: cambiar los "response" por el metodo @see Functions::makeArrayResponse
        if( !$trans ){
            $response = [
                "result" => 'error',
                "type" => "username",
                "message" => "No se pudo iniciar transaccion"
            ];
            return $response;
        }


        //------------Verificacion de datos
        //Verificamos si el nombre de usuario ya existe
        $result = $this->isUserExist_ByUsername( $user->getUsername() );

        if( $result === 'error' ){
            Users::rollbackTransaction();
            $response = [
                "result" => 'error',
                "message" => "No se pudo obtener usuario por nombre de usuario"
            ];
            return $response;
        }
        else if( $result === true ) {
            Users::rollbackTransaction();
            $response = [
                "result" => false,
                "type" => "username",
                "message" => "Nombre de usuario ya existe",
            ];
            return $response;
        }

        //Verificamos que correo no exista
        $result = $this->isUserExist_ByEmail( $user->getEmail() );
        if( $result === 'error' ){
            Users::rollbackTransaction();
            $response = [
                "result" => 'error',
                "message" => "No se pudo obtener email"
            ];
            return $response;
        }
        else if( $result === true ) {
            Users::rollbackTransaction();
            $response = [
                "result" => false,
                "type" => 'email',
                "message" => "Email ya existe"
            ];
            return $response;
        }


        //Se verifica carrera
        $conCareer = new CareerControl();

        $result = $conCareer->getCareer_ById( $student->getCareer() );
        if( $result === 'error' ){
            Users::rollbackTransaction();
            $response = [
                "result" => 'error',
                "message" => "No se pudo obtener carrera por id"
            ];
            return $response;
        }
        else if( $result === null ) {
            Users::rollbackTransaction();
            $response = [
                "result" => false,
                "type" => 'career',
                "message" => "Career no existe"
            ];
            return $response;
        }

        //Se asigna carrera a student
        $student->setCareer( $result );


        //------------Iniciamos registro

        //Registramos usuario
        $result = $this->perUsers->insertUser( $user );
        if( $result === false ){
            Users::rollbackTransaction();
            $response = [
                "result" => 'error',
                "message" => "No se pudo registrar usuario"
            ];
            return $response;
        }


        //Registramos ultimo usuario (debe ser el mismo)
        //TODO: obtener con el nombre de usuario para evitar problemas
        $result = $this->perUsers->getUser_ByUsername( $user->getUsername() );
        if( $result === false ){
            Users::rollbackTransaction();
            $response = [
                "result" => 'error',
                "message" => "No se pudo obtener usuario registrado anteriormente"
            ];
            return $response;
        }
        else if( $result === null ) {
            Users::rollbackTransaction();
            $response = [
                "result" => false,
                "message" => "User no existe"
            ];
            return $response;
        }

        //Obtiene Id del usuario y se lo agrega al student
        $userObj = self::makeObject_User( $result[0] );


        //Registramos student
        $student->setUser( $userObj );
        $conStudents = new StudentControl();

        $result = $conStudents->insertStudent( $student );
        if( $result === false ){
            Users::rollbackTransaction();
            $response = [
                "result" => 'error',
                "message" => "No se pudo registrar student"
            ];
            return $response;
        }

        $trans = $this->perUsers::commitTransaction();
        if( !$trans ){
            $response = [
                "result" => 'error',
                "message" => "No se pudo hacer commit"
            ];
            return $response;
        }

        //Si sale bien
        $response = [
            "result" => true,
            "message" => "Se registro correctamente"
        ];
        return $response;
    }



    //-----------------------
    // EXTRAS
    //-----------------------
    /**
     * array['field']
     *
     * @param $data array
     * @return User
     */
    public static function makeObject_User( $data ){
        $user = new User();
        //setting data
        $user->setId( $data['user_id'] );
        $user->setEmail( $data['email'] );
        //$user->setRegister_Date( $data['date'] );
        //$user->setStatus( $data['status'] );
        $user->setRole( $data['role'] );
        //Returning object
        return $user;
    }




}