<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
use App\Model\StudentModel;
use App\Model\UserModel;
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            $result = $userServ->getStudent_ByUserId( $params['id'] );
            return Utils::makeResultJSONResponse($res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }




    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * TODO: debe recibir header para poder ser usado en redireccion al confirmar correo
     */
    public function createUser($req, $res)
    {
        try {

            $userServ = new UserService();

            /* @var $user UserModel */
            $user = new UserModel();
            $user->setEmail( $req->getAttribute('email_data') );
            $user->setPassword( $req->getAttribute('password_data') );
            $user->setRole( $req->getAttribute('role_data') );

            $userServ->insertUser( $user );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Usuario registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            /* @var $user UserModel */
            $user = $req->getAttribute('user_data');
            //Se le asigna rol de estudiante (basic)
            $user->setRole( Utils::$ROLE_BASIC );
            /* @var $student StudentModel */
            $student = $req->getAttribute('student_data');
            $student->setUser($user);

            $userServ->insertUserAndStudent( $student );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Estudiante registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }




    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     *
     * @return Response
     */
    public function updateUserEmail($req, $res, $params)
    {
        try {
            $user = new UserModel();
            $user->setId(  $params['id'] );
            $user->setEmail( $req->getAttribute('email_data') );

            $userServ = new UserService();
            $userServ->updateUserEmail( $user  );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Email Actualizado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     *
     * @return Response
     */
    public function updateUserPassword($req, $res, $params)
    {
        try {
            $user = new UserModel();
            $pass = $req->getAttribute('password_data');

            $userServ = new UserService();
            $userServ->updateUserPassword( $params['id'], $pass  );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Password Actualizado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     *
     * @return Response
     */
    public function updateUser($req, $res, $params){
        try {
            $user = new UserModel();
            $user->setEmail( $req->getAttribute('email_data') );
            $user->setRole( $req->getAttribute('role_data') );

            $userServ = new UserService();
            $userServ->updateUser( $params['id'], $user );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Usuario Actualizado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


//    /**
//     * @param $req Request
//     * @param $res Response
//     * @param $params
//     *
//     * @return Response
//     */
//    public function updateUserRole($req, $res, $params)
//    {
//        try {
//            $user = new UserModel();
//            $user->setId(  $params['id'] );
//            $user->setRole( $req->getAttribute('role_data') );
//
//            $userServ = new UserService();
//            $userServ->updateUserRole( $user  );
//            return Utils::makeMessageResponse( $res, Utils::$OK, "Rol Actualizado con éxito");
//
//        } catch (RequestException $e) {
//            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
//        }
//    }


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
            return Utils::makeMessageResponse( $res, Utils::$OK, "Status modificado con éxito");

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
    public function deleteUser($req, $res, $params)
    {
        try {
            $userServ = new UserService();
            $userServ->deleteUser( $params['id'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Eliminado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}