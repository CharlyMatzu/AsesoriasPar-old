<?php namespace App\Controller;


use App\Exceptions\RequestException;
use App\Model\StudentModel;
use App\Model\UserModel;
use App\Service\AuthService;
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
            $user = $req->getAttribute('user_auth');
            $result = $authServ->signIn($user->getEmail(), $user->getPassword());
            return Utils::makeResultJSONResponse($res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse($res, $e->getStatusCode(), $e->getMessage());
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
            /* @var $user UserModel */
            $user = $req->getAttribute('user_data');
            //Se le asigna rol de estudiante (basic)
            $user->setRole( Utils::$ROLE_BASIC );
            /* @var $student StudentModel */
            $student = $req->getAttribute('student_data');
            $student->setUser($user);

            $authServ->signUp( $student );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Estudiante registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

}