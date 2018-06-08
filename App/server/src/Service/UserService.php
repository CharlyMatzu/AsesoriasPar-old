<?php namespace App\Service;


use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;

use App\Model\MailModel;
use App\Model\Student;
use App\Persistence\StudentsPersistence;
use App\Persistence\UsersPersistence;
use App\Model\User;
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
            throw new InternalErrorException(static::class.":getUsers", "Ocurrio un error al obtener usuarios", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
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
    public function getUsers_ByStudentId($student_id){
        $result = $this->userPer->getUser_ByStudentId($student_id);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":getUser_ByStudentid", "Ocurrio un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe usuario asociado");
        else
            return $result->getData()[0];
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
            throw new InternalErrorException(static::class.":getStaffUsers",
                "Ocurrio un error al obtener usuarios mod/admin", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay usuarios");
        else
            return $result->getData();
    }



    public function getUsersByStatus($status)
    {
        if( $status == Utils::$STATUS_ENABLE ){
            $result = $this->userPer->getEnableUsers();

            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException(static::class."getUsersByStatus","Ocurrio un error al obtener usuarios habilitados", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NoContentException("No hay usuarios");

            return $result->getData();
        }
        else if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->userPer->getDisabledUsers();

            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException(static::class."getUsersByStatus","Ocurrio un error al obtener usuarios deshabilitados", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NoContentException("No hay usuarios");

            return $result->getData();
        }
        else{
            $result = $this->userPer->getNoconfirmUsers();

            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException(static::class."getUsersByStatus","Ocurrio un error al obtener usuarios no confirmados", $result->getErrorMessage());
            else if( Utils::isEmpty($result->getOperation()) )
                throw new NoContentException("No hay usuarios");

            return $result->getData();
        }


    }



    /**
     * @param $id
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getUser_ById($id){
        $result = $this->userPer->getUser_ById( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":getUserById","Ocurrio un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No se encontro usuario");
        else
            return $result->getData();
    }


    /**
     * @param $id
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getStudent_ByUser($id){

        $this->getUser_ById($id);

        $studentPer = new StudentsPersistence();
        $result = $studentPer->getStudent_ByUserId( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":getStudent_ByUser",
                "Ocurrio un error al obtener estudiante", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No se encontro estudiante");
        else
            return $result->getData()[0];
    }

    /**
     * @throws InternalErrorException
     * @throws NoContentException
     * @return \mysqli_result
     */
    private function getLastUser(){
        $result = $this->userPer->getUser_Last();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class."getLastUser", "Ocurrio un error al obtener usuario", $result->getErrorMessage());
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
            throw new InternalErrorException(static::class.":getRoleUser", "Ocurrio un error al obtener rol de usuario", $result->getErrorMessage());
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
            throw new InternalErrorException(static::class."getUserByEmail","Ocurrio un error al obtener usuario por email", $result->getErrorMessage());
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
            throw new InternalErrorException(static::class."searchUsersByEmail","Ocurrio un error al obtener usuarios por email", $result->getErrorMessage());
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
            throw new InternalErrorException(static::class.":searchStaffUserByEmail",
                "Ocurrio un error al obtener usuarios por email", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontraron usuarios");

        return $result->getData();
    }


    private function isEmailUsed($email){
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
            throw new InternalErrorException( static::class."insertUser","Ocurrio un error al verificar email de usuario", $result->getErrorMessage());
        else if( $result->getOperation() == true )
            throw new ConflictException( "Email ya existe" );

        //Se verifica rol
        $result = $this->isRoleExists( $user->getRole() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":insertUser","Ocurrio un error al verificar rol", $result->getErrorMessage());
        else if( $result->getOperation() == false )
            throw new NotFoundException( "No existe rol asignado" );

        //Se registra usuario
        $result = $this->userPer->insertUser( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":insertUser","Ocurrio un error al registrar usuario", $result->getErrorMessage());
    }


    //------------------REGISTRAR USUARIO Y ESTUDIANTE

    /**
     * @param $student Student
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function insertUserAndStudent($student){

        //Inicia transaccion
        $trans = UsersPersistence::initTransaction();
        if( !$trans )
            throw new InternalErrorException(static::class.":insertUserAndStudent","Error al iniciar transaccion");


        //------------Verificacion de datos de usuario (excepciones incluidas)
        try {
            //Registramos usuario
            $this->insertUser( $student->getUser() );
            //Obtenemos ultimo registrado
            $result = $this->getLastUser();
            $user = self::makeUserModel( $result[0] );
            //Se agrega al Modelo de estudiante
            $student->setUser( $user );

        } catch (RequestException $e) {
            //Se termina transaccion
            UsersPersistence::rollbackTransaction();
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
            //Se termina transaccion
            UsersPersistence::rollbackTransaction();
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }

        //------------Iniciamos registro de estudiante
        try{
            $studentService = new StudentService();
            $studentService->insertStudent( $student );
        }catch (RequestException $e){
            //Se termina transaccion
            UsersPersistence::rollbackTransaction();
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }

        //Si marcha bien, se registra commit
        $trans = UsersPersistence::commitTransaction();
        if( !$trans )
            throw new InternalErrorException(static::class.":insertUserAndStudent","Error al realizar commit de transaccion");

        //Envia correo de confirmacion
        try{
            $this->sendConfirmEmail( $user->getEmail() );
            //TODO: Envia correo a admin
            $this->sendEmailToStaff( "Nuevo estudiante", "Se ha registrado un nuevo estudiante: ".$student->getFirstName()." ".$student->getLastName() );
        }catch (RequestException $e){}
    }

    /**
     * @param $subject String
     * @param $body String
     *
     */
    public function sendEmailToStaff($subject, $body){
        try{
            $mailServ = new MailService();
            $mail = new MailModel();
            $mail->setSubject($subject);
            $mail->setBody($body);
            $mail->setPlainBody($body);

            $users = $this->getStaffUsers();
            foreach( $users as $u ){
                $mail->addAdress( $u['email'] );
            }
            $mailServ->sendMail( $mail );
        }catch (RequestException $e){}
    }


    /**
     * @param $email String
     * @throws InternalErrorException
     */
    public function sendConfirmEmail($email){
        //Se envia correo de confirmacion TODO: debe enviarse a una cola
        $msg = "Se ha registrado en la plataforma de Asesoriaspar.ronintopics.com, para confirmar su correo haga clic en el siguiente enlace
                <a href='#'> http://client.asesoriaspar.com/#!confirmar/ </a>";
        $mailServ = new MailService();

        $mail = new MailModel();
        $mail->addAdress( $email );
        $mail->setSubject("Confirmacion de correo");
        $mail->setBody("<h3>Asesorias par</h3> <p>Favor de verificar su correo haciendo click en el siguiente enlace: <a href='".CLIENT_URL."confirm' </p>");
        $mail->setPlainBody("asdsad");

        try{
            $mailServ->sendMail( $mail );
        }catch (InternalErrorException $e){
            throw new InternalErrorException(static::class.":insertUserAndStudent","Error al enviar correo de confirmacion");
        }
    }


    /**
     * @param $user User
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function updateUser($user){
        $result = $this->userPer->getUser_ById( $user->getId() );

        //TODO: Cuando se haga update del correo, debe cambiarse status para confirmar
        //TODO: no debe eliminarse usuario con cron
//
//        //Verificacion de usuario
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( static::class.":updateUser","Ocurrio un error al obtener usuario");
//        else if( Utils::isEmpty( $result->getOperation() ) )
//            throw new NotFoundException("No existe usuario");

        //Verifica que email
        $user_db = self::makeUserModel( $result->getData()[0] );

        //Si cambio el email
        if( $user_db !== $user->getEmail() ){
            //Se obtiene
            $result = $this->isEmailUsed( $user->getEmail() );
            //Operacion
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( static::class.":updateUser","Ocurrio un error al verificar email de usuario", $result->getErrorMessage());
            else if( $result->getOperation() == true )
                throw new ConflictException( "Email ya existe" );
        }


        //Si cambio el rol
        if( $user_db !== $user->getRole() ){
            //Se verifica rol
            $result = $this->isRoleExists( $user->getRole() );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( static::class.":updateUser","Ocurrio un error al verificar rol", $result->getErrorMessage());
            else if( $result->getOperation() == false )
                throw new NotFoundException( "No existe rol asignado" );
        }


        //Se actualiza usuario
        $result = $this->userPer->updateUser( $user );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":updateUser","Ocurrio un error al actualizar usuario", $result->getErrorMessage());

    }

    /**
     * @param $user_id int
     * @param $status int
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($user_id, $status ){

        //Verificando si existe usuario
        $this->getUser_ById( $user_id );

        //Eliminando usuario (cambiando status)
        if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->userPer->changeStatusToDisable( $user_id );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( static::class.":changeStatus","Ocurrio un error al deshabilitar usuario", $result->getErrorMessage());
        }
        else if( $status == Utils::$STATUS_ENABLE ){
            $result = $this->userPer->changeStatusToEnable( $user_id );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( static::class.":changeStatus","Ocurrio un error al habilitar usuario", $result->getErrorMessage());
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
            throw new InternalErrorException( static::class.":deleteUser","Ocurrio un error al obtener usuario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe usuario");

        //Eliminando usuario (cambiando status)
        $result = $this->userPer->deleteUser_ById( $id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":deleteUser","Ocurrio un error al eliminar usuario", $result->getErrorMessage());
    }




    //-----------------------
    // EXTRAS
    //-----------------------
    /**
     * array['field']
     *
     * @param $data \mysqli_result
     * @return User
     */
    public static function makeUserModel($data ){
        $user = new User();
        //setting data
        $user->setId( $data['id'] );
        $user->setEmail( $data['email'] );
//        $user->setPassword( $data['password'] );
        $user->setRegister_Date( $data['register_date'] );
        $user->setStatus( $data['status'] );
        $user->setRole( $data['role'] );
        //Returning object
        return $user;
    }




}