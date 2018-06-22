<?php namespace App\Service;


use App\Auth;
use App\Exceptions\Persistence\TransactionException;
use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NoContentException;
use App\Exceptions\Request\NotFoundException;
use App\Exceptions\Request\RequestException;
use App\Model\StudentModel;
use App\Persistence\StudentsPersistence;
use App\Persistence\UsersPersistence;
use App\Model\UserModel;
use App\Utils;

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
            throw new InternalErrorException("getUsers", "Ocurrió un error al obtener usuarios", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
    }




    /**
     * @return mixed
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getStaffUsers()
    {
        $result = $this->userPer->getStaffUsers();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getStaffUsers",
                "Ocurrió un error al obtener usuarios mod/admin", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
    }


    /**
     * @param $id
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getUser_ById($id){

        $result = $this->userPer->getUser_ById( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getUserById","Ocurrió un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No se encontró usuario");
        else
            return $result->getData()[0];
    }

    /**
     * Obtiene usuario por id de estudiante
     *
     * @param $student_id int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getUser_ByStudentId($student_id){
        $result = $this->userPer->getUser_ByStudentId($student_id);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getUser_ByStudentid", "Ocurrió un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe usuario asociado");
        else
            return $result->getData()[0];
    }


    /**
     * @param $id
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws \App\Exceptions\Request\UnauthorizedException
     */
    public function getStudent_ByUserId($id){

        //Comprueba que exista usuario
        $user = $this->getUser_ById($id);
        //Verifica si es estudiante
        if( !Auth::isRoleBasic($user['role']) )
            throw new NotFoundException("No se encontró estudiante asociado");

        //Obtiene estudiante
        $studentPer = new StudentsPersistence();

        //------------AUTH CONDITION
        //Dependiendo de Rol, se obtiene cierta info
        $result = null;
        if( Auth::isStaffUser() )
            $result = $studentPer->getStudent_ByUserId( $id );
        else
            $result = $studentPer->getStudent_ByEnabledUserId( $id );
        //------------AUTH CONDITION


        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getStudent_ByUser",
                "Ocurrió un error al obtener estudiante", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No se encontró estudiante");
        else
            return $result->getData()[0];
    }

    /**
     * @throws InternalErrorException
     * @throws NoContentException
     * @return \mysqli_result
     */
    public function getLastUser(){
        $result = $this->userPer->getUser_Last();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getLastUser", "Ocurrió un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
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
            throw new InternalErrorException("getRoleUser", "Ocurrió un error al obtener rol de usuario", $result->getErrorMessage());
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
            throw new InternalErrorException("getUserByEmail","Ocurrió un error al obtener usuario por email", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontro usuario");
        else
            return $result->getData();
    }


    /**
     * @param $email
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function searchUserByEmail($email)
    {
        $result = $this->userPer->searchUsers_ByEmail( $email );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("searchUsersByEmail","Ocurrió un error al obtener usuarios por email", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontraron usuarios");

        return $result->getData();
    }

    /**
     * @param $email
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function searchStaffUser_ByEmail($email)
    {
        $result = $this->userPer->searchStaffUsers_ByEmail( $email );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("searchStaffUserByEmail",
                "Ocurrió un error al obtener usuarios por email", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontraron usuarios");

        return $result->getData();
    }


    /**
     * @param $email
     *
     * @return \App\Model\DataResult
     * @throws InternalErrorException
     */
    private function isEmailUsed($email){
        $result = $this->userPer->getUser_ByEmail( $email );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }


    /**
     * @param $role
     *
     * @return \App\Model\DataResult
     * @throws InternalErrorException
     */
    public function isRoleExists($role){
        $result = $this->userPer->getRole_ByName( $role );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }

    /**
     * @param $status
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getUsersByStatus($status)
    {
        if( $status == Utils::$STATUS_ACTIVE ){
            $result = $this->userPer->getEnableUsers();

            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException("getUsersByStatus","Ocurrió un error al obtener usuarios habilitados", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NoContentException("No hay usuarios");

            return $result->getData();
        }
        else if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->userPer->getDisabledUsers();

            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException("getUsersByStatus","Ocurrió un error al obtener usuarios deshabilitados", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NoContentException("No hay usuarios");

            return $result->getData();
        }
        else{
            $result = $this->userPer->getNoConfirmUsers();

            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException("getUsersByStatus","Ocurrió un error al obtener usuarios no confirmados", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NoContentException("No hay usuarios");

            return $result->getData();
        }


    }

    //------------------REGISTRAR USUARIO


    /**
     * @param $user UserModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function insertUser($user){

        //TODO: debe enviar correo para que el usuario sea confirmado
        //TODO: cron para eliminar usuario si este no se confirma en una semana
        //TODO: puede solicitarse un correo para confirmar

        //Verifica que email no exista
        $result = $this->isEmailUsed( $user->getEmail() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertUser","Ocurrió un error al verificar email de usuario", $result->getErrorMessage());
        else if( $result->getOperation() == true )
            throw new ConflictException( "Email ya existe" );

        //Se verifica rol
        $result = $this->isRoleExists( $user->getRole() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertUser","Ocurrió un error al verificar rol", $result->getErrorMessage());
        else if( $result->getOperation() == false )
            throw new NotFoundException( "No existe rol asignado" );

        //Se registra usuario
        $result = $this->userPer->insertUser( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertUser","Ocurrió un error al registrar usuario", $result->getErrorMessage());
    }


    //------------------REGISTRAR USUARIO Y ESTUDIANTE

    /**
     * @param $student StudentModel
     *
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function insertUserAndStudent($student){

        //Inicia transacción
        try{
            UsersPersistence::initTransaction();
        }catch (TransactionException $e){
            throw new InternalErrorException('insertUserAndStudent', $e->getMessage());
        }


        //------------Verificación de datos de usuario (excepciones incluidas)
        try {
            //Registramos usuario
            $this->insertUser( $student->getUser() );
            //Obtenemos ultimo registrado
            $result = $this->getLastUser();
            $user = self::makeUserModel( $result[0] );
            //Se agrega al Modelo de estudiante
            $student->setUser( $user );

        } catch (RequestException $e) {
            //Se termina transacción
            try {
                UsersPersistence::rollbackTransaction();
            } catch (TransactionException $e) {
                throw new InternalErrorException('insertUserAndStudent', $e->getMessage());
            }
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }


        //--------------CARRERA

        try {
            $careerService = new CareerService();
            $result = $careerService->getCareer_ById( $student->getCareer() );
            $career = CareerService::makeObject_career( $result[0] );
            //Se asigna carrera (model) a student
            $student->setCareer( $career );

        } catch (RequestException $e) {
            //Se termina transacción
            try {
                UsersPersistence::rollbackTransaction();
            } catch (TransactionException $e) {
                throw new InternalErrorException('insertUserAndStudent', $e->getMessage());
            }
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }

        //------------Iniciamos registro de estudiante
        try{
            $studentService = new StudentService();
            $studentService->insertStudent( $student );
        }catch (RequestException $e){
            //Se termina transacción
            try {
                UsersPersistence::rollbackTransaction();
            } catch (TransactionException $e) {
                throw new InternalErrorException('insertUserAndStudent', $e->getMessage());
            }
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }

        //Si marcha bien, se registra commit
        try {
            UsersPersistence::commitTransaction();
        } catch (TransactionException $e) {
            throw new InternalErrorException('insertUserAndStudent', $e->getMessage());
        }

        //Envía correo de confirmación
        try{
            $mailServ = new MailService();
            $mailServ->sendConfirmEmail(  $user );
            $staff = $this->getStaffUsers();
            //Se envía a staff
            $mailServ->sendEmailToStaff( "Nuevo estudiante", "Se ha registrado un nuevo estudiante: ".$student->getFirstName()." ".$student->getLastName(), $staff );
        }catch (RequestException $e){}
    }


    /**
     * @param $user UserModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws \App\Exceptions\Request\ForbiddenException
     * @throws \App\Exceptions\Request\UnauthorizedException
     */
    public function updateUserEmail($user){

        $result = $this->getUser_ById( $user->getId() );

        //Verifica que email
        $user_db = self::makeUserModel( $result );

        //TODO: Cuando se haga update del correo, debe cambiarse status para confirmar
        //TODO: no debe eliminarse usuario con cron
        //Si cambio el email
        if( $user_db !== $user->getEmail() ){
            //Se obtiene
            $result = $this->isEmailUsed( $user->getEmail() );
            //Operación
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "updateUser","Ocurrió un error al verificar email de usuario", $result->getErrorMessage());
            else if( $result->getOperation() == true )
                throw new ConflictException( "Email ya existe" );
        }

        //Se actualiza usuario
        $result = $this->userPer->updateUserEmail( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "updateUser","Ocurrió un error al actualizar email", $result->getErrorMessage());

    }

    /**
     * @param $user UserModel
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function updateUserPassword($user){

        $this->getUser_ById( $user->getId() );

        //Se actualiza usuario
        $result = $this->userPer->updateUserPassword( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "updateUserPassword","Ocurrió un error al actualizar password", $result->getErrorMessage());
    }


    /**
     * @param $user UserModel
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function updateUserRole($user){

        $result = $this->getUser_ById( $user->getId() );
        $user_db = self::makeUserModel( $result );

        //Si cambio el rol
        if( $user_db !== $user->getRole() ){

            //Si es estudiante
            if( Auth::isRoleBasic( $user_db->getRole() ) )
                throw new ConflictException("No puede cambiarse rol de estudiante");

            //Se verifica rol
            $result = $this->isRoleExists( $user->getRole() );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "updateUserRole","Ocurrió un error al verificar rol", $result->getErrorMessage());
            else if( $result->getOperation() == false )
                throw new NotFoundException( "No existe rol asignado" );
        }

        //Se actualiza usuario
        $result = $this->userPer->updateUserRole( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "updateUserRole","Ocurrió un error al actualizar rol", $result->getErrorMessage());
    }



    /**
     * @param $user_id int
     * @param $status int
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws \App\Exceptions\Request\UnauthorizedException
     */
    public function changeStatus($user_id, $status ){

        //Verificando si existe usuario
        $this->getUser_ById( $user_id );

        //Eliminando usuario (cambiando status)
        if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->userPer->changeStatusToDisable( $user_id );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "changeStatus","Ocurrió un error al deshabilitar usuario", $result->getErrorMessage());
        }
        else if( $status == Utils::$STATUS_ACTIVE ){
            $result = $this->userPer->changeStatusToEnable( $user_id );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "changeStatus","Ocurrió un error al habilitar usuario", $result->getErrorMessage());
        }

    }

    /**
     * @param $id
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function deleteUser($id)
    {
        //Verificando si existe usuario
        $result = $this->userPer->getUser_ById( $id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "deleteUser","Ocurrió un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe usuario");

        //Eliminando usuario (cambiando status)
        $result = $this->userPer->deleteUser_ById( $id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "deleteUser","Ocurrió un error al eliminar usuario", $result->getErrorMessage());
    }




    //-----------------------
    // EXTRAS
    //-----------------------
    /**
     * array['field']
     *
     * @param $data \mysqli_result
     *
     * @return UserModel
     */
    public static function makeUserModel($data ){
        $user = new UserModel();
        //setting data
        $user->setId( $data['id'] );
        $user->setEmail( $data['email'] );
//        $user->setPassword( $data['password'] );
        $user->setdate_register( $data['date_register'] );
        $user->setStatus( $data['status'] );
        $user->setRole( $data['role'] );
        //Returning object
        return $user;
    }




}