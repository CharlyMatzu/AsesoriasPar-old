<?php namespace Controller;

use Exceptions\RequestException;
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
        return $res;
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     */
    public function getUser_ById($req, $res, $params){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function createUser($req, $res){}


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