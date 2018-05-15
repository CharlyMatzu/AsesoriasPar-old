<?php namespace Controller;

use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\RequestException;
use PHPMailer\PHPMailer\Exception;
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
            return $res->withStatus(200)->withJson($result);

        } catch (RequestException $e) {
            return $res->withStatus( $e->getRequestStatusCode() )
                       ->withJson( Utils::makeArrayResponse( $e->getMessage() ) );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     */
    public function getUser_ById($req, $res, $params){
        try {
            $userServ = new UserService();
            $result = $userServ->getUsers();
            return $res->withStatus(200)->withJson($result);

        } catch (RequestException $e) {
            return $res->withStatus( $e->getRequestStatusCode() )
                ->withJson( Utils::makeArrayResponse( $e->getMessage() ) );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     */
    public function createUser($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function signIn($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function updateUser($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteUser($req, $res){}


}