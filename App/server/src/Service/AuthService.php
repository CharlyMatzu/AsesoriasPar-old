<?php namespace App\Service;

use App\Auth;
use App\Exceptions\Auth\TokenException;
use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NotFoundException;
use App\Exceptions\Request\RequestException;
use App\Exceptions\Request\UnauthorizedException;
use App\Model\MailModel;
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
     * @throws UnauthorizedException
     */
    public function signIn($email, $pass){
        $result = $this->userPer->getUser_BySignIn($email, $pass);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("signIn","Ocurrió un error al autenticar", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new UnauthorizedException("email o contraseña incorrectos");

        //Si esta sin confirmar
        $user = $result->getData()[0];

        if( $user['status'] == Utils::$STATUS_DISABLE ) {
            throw new UnauthorizedException("email o contraseña e incorrectos");
        }
        else if( $user['status'] == Utils::$STATUS_PENDING ) {

            try{
                $mailServ = new MailService();
                $mailServ->sendConfirmEmail( UserService::makeUserModel($user) );
            }catch (InternalErrorException $e) {}

            throw new UnauthorizedException("Debe confirmar correo");
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
     * @throws \App\Exceptions\Request\RequestException
     */
    public function signUp($student){
        $userServ = new UserService();
        $userServ->insertUserAndStudent( $student );
    }

    /**
     * @param $token
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function confirmUser($token)
    {
        //Obtiene datos de token
        try{
            $id = Auth::getData($token);
        } catch (TokenException $e) {
            throw new ConflictException( $e->getMessage() );
        }
        //Verifica que exista usuario
        $userServ = new UserService();
        $user = $userServ->getUser_ById( $id );

        //Se comprueba estado actual
        if( $user['status'] == Utils::$STATUS_ACTIVE )
            throw new ConflictException("Ya se ha confirmado correo");

        if( $user['status'] == Utils::$STATUS_DISABLE )
            throw new NotFoundException("No existe usuario");

        //Se cambia status
        $userServ->changeStatus( $id, Utils::$STATUS_ACTIVE );

        //Envío de correo de
        try{
            $text = "Confirmado con éxito el correo: ".$user['email'];
            $mail = new MailModel();
            $mail->setSubject("asesorías par: confirmado");
            $mail->setBody($text);
            $mail->setPlainBody($text);
            $mail->addAdress( $user['email'] );

            $mailServ = new MailService();
            $mailServ->sendMail( $mail );
        } catch (RequestException $e){}
    }


}