<?php namespace App\Controller;


use App\Exceptions\Request\RequestException;
use App\Model\StudentModel;
use App\Model\UserModel;
use App\Service\AuthService;
use App\Service\UserService;
use App\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController
{
    /**
     * @param $req Request
     * @param $res Response
     *
     * @return mixed|Response
     */
    public function signin($req, $res)
    {
        try {
            $authServ = new AuthService();
            $email = $req->getAttribute('email_data');
            $pass = $req->getAttribute('password_data');
            $result = $authServ->signIn( $email, $pass );
            return Utils::makeResultJSONResponse($res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageResponse($res, $e->getStatusCode(), $e->getMessage());
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function signup($req, $res)
    {
        try {
            $authServ = new AuthService();

            $user = new UserModel();
            $user->setEmail( $req->getAttribute('email_data') );
            $user->setPassword( $req->getAttribute('password_data') );
            //Se le asigna rol de estudiante (basic)
            $user->setRole( Utils::$ROLE_BASIC );
            /* @var $student StudentModel */
            $student = $req->getAttribute('student_data');
            $student->setUser($user);

            $authServ->signUp( $student );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Estudiante registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function confirm($req, $res, $params)
    {
        try {
            $authServ = new AuthService();
            $authServ->confirmUser( $params['token'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Usuario confirmado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

}