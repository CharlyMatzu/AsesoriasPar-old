<?php namespace Service;

use Exceptions\BadRequestException;
use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Objects\DataResult;
use Persistence\UsersPersistence;
use Objects\Student;
use Objects\User;
use Utils;

class UserService{

    private $userPer;

    public function __construct(){
        $this->userPer = new UsersPersistence();
    }


    /**
     * Obtiene todos los usuarios registrados
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getUsers(){
        $result = $this->userPer->getUsers();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener usuarios");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
    }


    /**
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getActiveUsers(){
        $result = $this->userPer->getActive();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener usuarios");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
    }


    /**
     * @param $email string
     * @param $pass string
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function signIn($email, $pass){
        $result = $this->userPer->getUser_BySignIn($email, $pass);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al authenticar");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("email o contraseña incorrectos");

        //Si se encontró, se crea token y se retorna
        else{
            $user = self::makeObject_User( $result->getData()[0] );
            //Se envia array con datos: id y email y retorna token
            //TODO: no usar id de BD
//            $token = Auth::getToken([
//                'id' => $user->getId(),
//                'email' => $user->getEmail()
//            ]);
            $token = Auth::getToken($user->getId());

            return [
                "message" => "autenticado con exito",
                'token' => $token
            ];
        }

    }

    /**
     * @param $id
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getUser_ById($id){
        $result = $this->userPer->getUser_ById( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener usuario");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontro usuario");
        else
            return $result->getData();
    }


    /**
     * @param $id
     * @return bool|DataResult
     */
    public function isUserExist_ById($id){
        $result = $this->userPer->getUser_ById($id);

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }


    /**
     * @param $id
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getRoleUser($id){

        $result = $this->userPer->getRoleUser( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener rol de usuario");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se obtuvo rol");
        else
            return $result->getData();
    }


    /**
     * @param $email
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getUser_ByEmail($email){
        $result = $this->userPer->getUser_ByEmail( $email );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener usuario por email");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontro usuario");
        else
            return $result->getData();
    }


    public function isEmailUsed($email){
        $result = $this->userPer->getUser_ByEmail( $email );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }


    public function isRoleExists($role){
        $result = $this->userPer->getRole_ByName( $role );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }

    //------------------REGISTRAR USUARIO


    /**
     * @param $user User
     * @return array
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function insertUser($user){

        //Verifica que email no exista
        $result = $this->isEmailUsed( $user->getEmail() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al verificar email de usuario");
        else if( $result->getOperation() == true )
            throw new ConflictException( "Email ya existe" );

        //Se verifica rol
        $result = $this->isRoleExists( $user->getRole() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al verificar rol");
        else if( $result->getOperation() == false )
            throw new NotFoundException( "No existe rol asignado" );

        //Se registra usuario
        $result = $this->userPer->insertUser( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al registrar usuario");

        return Utils::makeArrayResponse(
            "Se registro usuario con éxito"
        );

    }


    /**
     * @param $user User
     * @return array
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function updateUser($user){
        $result = $this->userPer->getUser_ById( $user->getId() );

        //Verificacion de usuario
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al obtener usuario");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe usuario");

        //Verifica que email
        $user_db = self::makeObject_User( $result->getData()[0] );

        //Si cambio el email
        if( $user_db !== $user->getEmail() ){
            //Se obtiene
            $result = $this->isEmailUsed( $user->getEmail() );
            //Operacion
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "Ocurrio un error al verificar email de usuario");
            else if( $result->getOperation() == true )
                throw new ConflictException( "Email ya existe" );
        }

        //Si cambio el rol
        if( $user_db !== $user->getRole() ){
            //Se verifica rol
            $result = $this->isRoleExists( $user->getRole() );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "Ocurrio un error al verificar rol");
            else if( $result->getOperation() == false )
                throw new NotFoundException( "No existe rol asignado" );
        }


        //Se actualiza usuario
        $result = $this->userPer->updateUser( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al actualizar usuario");

        return Utils::makeArrayResponse(
            "Se actualizó usuario con éxito"
        );

    }

    /**
     * @param $id
     * @return array
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws NotFoundException
     */
    public function deleteUser( $id ){

        //Verificando si existe usuario
        $result = $this->userPer->getUser_ById( $id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al obtener usuario");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe usuario");

        //Eliminando usuario (cambiando status)
        $result = $this->userPer->changeStatusToDeleted( $id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al eliminar usuario");

        return Utils::makeArrayResponse(
            "Se elimino el usuario con éxito"
        );

    }



//    public function searchUser($search_by, $data_search)
//    {
//        if ($search_by === "id") {
//            $result = $this->getUser_ById($data_search);
//            if ($result == false)
//                throw new ConflictException("Usuario no existe por id");
//        } else if ($search_by === "email") {
//            $result = $this->getUser_ByEmail($data_search);
//            if ($result == false)
//                throw new ConflictException("Usuario no existe por email");
//        } else if($search_by === "users"){
//            $result = $this->getUsers();
//            if ($result == false)
//                throw new ConflictException("Usuarios no existen");
//        } else{
//            $response = [
//                "result" => "Error en los datos",
//                "message" => "Verifique los datos"
//            ];
//            return $response;
//        }
//
//        if( $result['operation'] == Utils::$OPERATION_ERROR )
//            throw new InternalErrorException("Ocurrio un error al registrar usuario", $result['error']);
//        else
//            return Utils::makeArrayResponse(
//                "Busqueda exitosa",
//                $result
//            );
//    }



        //------------------REGISTRAR USUARIO Y ESTUDIANTE

//    /**
//     * @param $user User
//     * @param $student Student
//     * @return array
//     */
//    public function insertUserAndStudent($user, $student){
//        $trans = Users::initTransaction();
//        //TODO: cambiar los "response" por el metodo @see Functions::makeArrayResponse
//        if( !$trans ){
//            $response = [
//                "result" => 'error',
//                "type" => "username",
//                "message" => "No se pudo iniciar transaccion"
//            ];
//            return $response;
//        }
//
//
//        //------------Verificacion de datos
//        //Verificamos si el nombre de usuario ya existe
//        $result = $this->isUserExist_ByUsername( $user->getUsername() );
//
//        if( $result === 'error' ){
//            Users::rollbackTransaction();
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo obtener usuario por nombre de usuario"
//            ];
//            return $response;
//        }
//        else if( $result === true ) {
//            Users::rollbackTransaction();
//            $response = [
//                "result" => false,
//                "type" => "username",
//                "message" => "Nombre de usuario ya existe",
//            ];
//            return $response;
//        }
//
//        //Verificamos que correo no exista
//        $result = $this->isUserExist_ByEmail( $user->getEmail() );
//        if( $result === 'error' ){
//            Users::rollbackTransaction();
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo obtener email"
//            ];
//            return $response;
//        }
//        else if( $result === true ) {
//            Users::rollbackTransaction();
//            $response = [
//                "result" => false,
//                "type" => 'email',
//                "message" => "Email ya existe"
//            ];
//            return $response;
//        }
//
//
//        //Se verifica carrera
//        $conCareer = new CareerControl();
//
//        $result = $conCareer->getCareer_ById( $student->getCareer() );
//        if( $result === 'error' ){
//            Users::rollbackTransaction();
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo obtener carrera por id"
//            ];
//            return $response;
//        }
//        else if( $result === null ) {
//            Users::rollbackTransaction();
//            $response = [
//                "result" => false,
//                "type" => 'career',
//                "message" => "Career no existe"
//            ];
//            return $response;
//        }
//
//        //Se asigna carrera a student
//        $student->setCareer( $result );
//
//
//        //------------Iniciamos registro
//
//        //Registramos usuario
//        $result = $this->perUsers->insertUser( $user );
//        if( $result === false ){
//            Users::rollbackTransaction();
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo registrar usuario"
//            ];
//            return $response;
//        }
//
//
//        //Registramos ultimo usuario (debe ser el mismo)
//        //TODO: obtener con el nombre de usuario para evitar problemas
//        $result = $this->perUsers->getUser_ByUsername( $user->getUsername() );
//        if( $result === false ){
//            Users::rollbackTransaction();
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo obtener usuario registrado anteriormente"
//            ];
//            return $response;
//        }
//        else if( $result === null ) {
//            Users::rollbackTransaction();
//            $response = [
//                "result" => false,
//                "message" => "User no existe"
//            ];
//            return $response;
//        }
//
//        //Obtiene Id del usuario y se lo agrega al student
//        $userObj = self::makeObject_User( $result[0] );
//
//
//        //Registramos student
//        $student->setUser( $userObj );
//        $conStudents = new StudentControl();
//
//        $result = $conStudents->insertStudent( $student );
//        if( $result === false ){
//            Users::rollbackTransaction();
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo registrar student"
//            ];
//            return $response;
//        }
//
//        $trans = $this->perUsers::commitTransaction();
//        if( !$trans ){
//            $response = [
//                "result" => 'error',
//                "message" => "No se pudo hacer commit"
//            ];
//            return $response;
//        }
//
//        //Si sale bien
//        $response = [
//            "result" => true,
//            "message" => "Se registro correctamente"
//        ];
//        return $response;
//    }



    //-----------------------
    // EXTRAS
    //-----------------------
    /**
     * array['field']
     *
     * @param $data \mysqli_result
     * @return User
     */
    public static function makeObject_User( $data ){
        $user = new User();
        //setting data
        $user->setId( $data['user_id'] );
        $user->setEmail( $data['email'] );
        $user->setPassword( $data['password'] );
        $user->setRegister_Date( $data['register_date'] );
        $user->setStatus( $data['status'] );
        $user->setRole( $data['role_name'] );
        //Returning object
        return $user;
    }




}