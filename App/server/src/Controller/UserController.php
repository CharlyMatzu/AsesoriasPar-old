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
     * @return Response
     */
    public function getStaffUsers($req, $res)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->getStaffUsers();
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
     * @param $params array
     * @return Response
     */
    public function getUsersByStatus($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->getUsersByStatus( $params['status'] );
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
    public function searchUsersByEmail($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->searchUserByEmail( $params['email'] );
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
    public function searchStaffUsersByEmail($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->searchStaffUser_ByEmail( $params['email'] );
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
    public function getStudent_ByUserId($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $result = $userServ->getStudent_ByUser( $params['id'] );
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
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Status modificado con exito");

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