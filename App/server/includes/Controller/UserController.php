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
    public function signUp($req, $res)
    {
        try {
            $userServ = new UserService();
            $user = $req->getAttribute('user_signup');
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
     * @return Response
     */
    public function updateUser($req, $res)
    {
        try {
            $userServ = new UserService();
            $user = $req->getAttribute('user_update');
            $result = $userServ->updateUser($user);
            return Utils::makeJSONResponse( $res, Utils::$OK, "Actualizado con exito", $result);

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
            $userServ->deleteUser( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Eliminado con exito", $params['id']);

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}