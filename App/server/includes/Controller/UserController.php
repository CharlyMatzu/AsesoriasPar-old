<?php namespace Controller;

use Exceptions\RequestException;
use Model\User;
use Service\UserService;
use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

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
            return Utils::makeJSONResponse( $res, Utils::$OK, "Usuarios", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeJSONResponse( $res, Utils::$OK, "Usuario", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function creteUser($req, $res)
    {
        try {
            $userServ = new UserService();
            $user = $req->getAttribute('user_data');
            $userServ->insertUser( $user );
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Usuario registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            $student = $req->getAttribute('student_signup');
            $userServ->insertUserAndStudent( $student );
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Estudiante registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeJSONResponse( $res, Utils::$OK, "Autenticado con exito", $result);

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            /* @var $user User*/
            $user = $req->getAttribute('user_data');
            $user->setId( $params['id'] );
            $userServ->updateUser( $user );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Actualizado con exito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            $userServ->disableUser( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Eliminado con exito", $params['id']);

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}