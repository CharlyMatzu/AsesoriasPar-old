<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Model\Student;
use App\Model\User;
use App\Service\UserService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class UserController
{

    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function getUsers($req, $res)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->getUsers();
            return Utils::makeResultJSONResponse($res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function getUser_ById($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->getUser_ById( $params['id'] );
            return Utils::makeResultJSONResponse($res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function createUser($req, $res)
    {
        try {
            $userServ = new UserService();
            $role = $user = $req->getAttribute('role_data');
            /* @var $user User */
            $user = $req->getAttribute('user_data');
            $user->setRole( $role );

            $userServ->insertUser( $user );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Usuario registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }



    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function createUserAndStudent($req, $res)
    {
        try {
            $userServ = new UserService();
            /* @var $user User */
            $user = $req->getAttribute('user_data');
            //Se le asigna rol de estudiante (basic)
            $user->setRole( Utils::$ROLE_BASIC );
            /* @var $student Student */
            $student = $req->getAttribute('student_data');
            $student->setUser($user);

            $userServ->insertUserAndStudent( $student );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Estudiante registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function auth($req, $res)
    {
        try {
            $userServ = new UserService();
            $user = $req->getAttribute('user_auth');
            $result = $userServ->signIn( $user->getEmail(), $user->getPassword() );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Autenticado con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     *
     * @return Response
     */
    public function updateUser($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $role = $user = $req->getAttribute('role_data');
            /* @var $user User */
            $user = $req->getAttribute('user_data');
            $user->setRole( $role );
            $user->setId( $params['id'] );

            $userServ->updateUser( $user );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Actualizado con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function changeStatusUser($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $userServ->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Desactivado con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function deleteUser($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $userServ->deleteUser( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Eliminado con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}