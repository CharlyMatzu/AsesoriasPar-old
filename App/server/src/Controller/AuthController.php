<?php namespace App\Controller;


use App\Exceptions\RequestException;
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
    public function authenticate($req, $res)
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

}