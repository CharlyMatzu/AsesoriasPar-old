<?php namespace Middelware;


use Objects\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

class InputParamsMiddelware extends Middelware
{

    //Cuando es un valor por GET, el método debe llamarse checkParam_NombreParametro[s]
    //Cuando es un valor mediante POST o similar, el método debe llamarse checkData_Nombre



    /**
     * Verifica que el parametro enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkParam_Id($req, $res, $next)
    {
        $id = $this->getRouteParams($req)['id'];
        //Verifica que sea un string numerico (no int porque viene como string)
        if( !is_numeric($id) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido",
                Utils::makeParamValidationArray( $req, $id ));

        $res = $next($req, $res);
        return $res;
    }

    /**
     * Verifica que el parametro enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Signup($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['email']) || !isset($params['password']) || !isset($params['role']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: email, password, role");

        if( empty($params['email']) || empty($params['password']) || empty($params['role']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        $email = $params['email'];
        $pass = $params['password'];
        $role = $params['role'];

        //TODO validar
//        if( !preg_match(Utils::EXPREG_EMAIL, $email) ||
//            !preg_match(Utils::EXPREG_PASS, $pass) ||
//            !Utils::isRole($role) )
//            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Se crea objeto
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($pass);
        $user->setRole($role);

        //Se envian los parametros mediante el request ya validados
        $req = $req->withAttribute('user_signup', $user);

        $res = $next($req, $res);
        return $res;
    }



}