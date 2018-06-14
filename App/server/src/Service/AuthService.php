<?php namespace App\Service;

use App\Auth;
use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;
use App\Model\StudentModel;
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
            throw new InternalErrorException("signIn","Ocurrió un error al authenticar", $result->getErrorMessage());
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
     * @param $student StudentModel
     *
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function signUp($student){
        $userServ = new UserService();
        $userServ->insertUserAndStudent( $student );
    }


}