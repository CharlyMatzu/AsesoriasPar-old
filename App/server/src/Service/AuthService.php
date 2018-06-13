<?php namespace App\Service;

use App\Auth;
use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;
use App\Model\Student;
use App\Persistence\UsersPersistence;
use App\Utils;

class AuthService
{
    private $userPer;

    public function __construct(){
        $this->userPer = new UsersPersistence();
    }


    /**
     * @param $email string
     * @param $pass string
     *
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     * TODO: solo debe funcionar si usuario esta activo
     */
    public function signIn($email, $pass){
        $result = $this->userPer->getUser_BySignIn($email, $pass);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class."signIn","Ocurrió un error al authenticar", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("email o contraseña incorrectos");

        //Si esta sin confirmar
        $user = $result->getData()[0];

        if( $user['status'] == Utils::$STATUS_ENABLE ) {
            throw new NotFoundException("Usuario o contraseña e incorrectos");
            //TODO reenviar correo de confirmación
        }
        else if( $user['status'] == Utils::$STATUS_NO_CONFIRM ) {
            throw new ConflictException("Usuario no ha confirmado correo electrónico");
            //TODO reenviar correo de confirmación
        }

        //TODO: no usar id de BD
        $token = Auth::getToken( $user['id'] );

        return [
            "user" => $user,
            "token" => $token,
        ];

    }

    /**
     * @param $student Student
     *
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function signUp($student){
        $userServ = new UserService();

        //Inicia transaccion
        $trans = UsersPersistence::initTransaction();
        if( !$trans )
            throw new InternalErrorException("insertUserAndStudent","Error al iniciar transaccion");


        //------------Verificacion de datos de usuario (excepciones incluidas)
        try {
            //Registramos usuario
            $userServ->insertUser( $student->getUser() );
            //Obtenemos ultimo registrado
            $result = $userServ->getLastUser();
            $user = UserService::makeUserModel( $result[0] );
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
            throw new InternalErrorException("insertUserAndStudent","Error al realizar commit de transaccion");

        //Envia correo de confirmacion
        try{
            $mailServ = new MailService();
            $mailServ->sendConfirmEmail( $user->getEmail() );
            $staff = $userServ->getStaffUsers();
            //TODO: Envia correo a admin
            $mailServ->sendEmailToStaff( "Nuevo estudiante", "Se ha registrado un nuevo estudiante: ".$student->getFirstName()." ".$student->getLastName(), $staff );
        }catch (RequestException $e){}
    }


}