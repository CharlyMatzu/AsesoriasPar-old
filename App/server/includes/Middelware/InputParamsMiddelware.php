<?php namespace Middelware;


use Model\Career;
use Model\Period;
use Model\Student;
use Model\Subject;
use Model\User;
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
    public function checkData_user($req, $res, $next)
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
        $req = $req->withAttribute('user_data', $user);

        $res = $next($req, $res);
        return $res;
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_student($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['first_name']) || !isset($params['last_name']) ||
            !isset($params['itson_id']) || !isset($params['phone']) ||
            !isset($params['career']))
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST,
                "Faltan parametros", "Se requiere: first_name, last_name, itson_id, phone");

        if( empty($params['first_name']) || empty($params['last_name']) ||
            empty($params['itson_id']) || empty($params['phone']) ||
            empty($params['career']))
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        $first = $params['first_name'];
        $last = $params['last_name'];
        $itson = $params['itson_id'];
        $phone = $params['phone'];
        $career = $params['career'];

        //TODO validar
//        if( !preg_match(Utils::EXPREG_EMAIL, $email) ||
//            !preg_match(Utils::EXPREG_PASS, $pass) ||
//            !Utils::isRole($role) )
//            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");



        //Se crea objeto estudiante
        $student = new Student();
        //Se agregan datos
//        $student->setUser( $user );
        $student->setFirstName($first);
        $student->setLastName($last);
        $student->setItsonId($itson);
        $student->setPhone($phone);
        $student->setCareer($career);


        //Se envian los parametros mediante el request ya validados
        $req = $req->withAttribute('student_data', $student);

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
    public function checkData_Auth($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['email']) || !isset($params['password']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: email, password");

        if( empty($params['email']) || empty($params['password']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        $email = $params['email'];
        $pass = $params['password'];

        //TODO validar
//        if( !preg_match(Utils::EXPREG_EMAIL, $email) ||
//            !preg_match(Utils::EXPREG_PASS, $pass))
//            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Se crea objeto
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($pass);

        //Se envian los parametros mediante el request ya validados
        $req = $req->withAttribute('user_auth', $user);

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
    public function checkData_career($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['name']) || !isset($params['short_name']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: name, short_name");

        if( empty($params['name']) || empty($params['short_name']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        $name = $params['name'];
        $short_name = $params['short_name'];

        //TODO validar
//        if( !preg_match(Utils::EXPREG_EMAIL, $email) ||
//            !preg_match(Utils::EXPREG_PASS, $pass) ||
//            !Utils::isRole($role) )
//            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Se crea objeto
        $career = new Career();
        $career->setName( $name );
        $career->setShortName( $short_name );

        //Se envian los parametros mediante el request ya validados
        $req = $req->withAttribute('career_data', $career);

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
    public function checkData_plan($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['year']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: year");

        if( empty($params['year']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //TODO: validar formato, tipo, etc..

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
    public function checkData_subject($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['name']) || !isset($params['short_name']) || !isset($params['description']) ||
            !isset($params['semester']) || !isset($params['plan']) || !isset($params['career']))
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros",
                "Se requiere: name, short_name, description, semester, plan, career");

        if( empty($params['name']) || empty($params['short_name']) || empty($params['description']) ||
            empty($params['semester']) || empty($params['plan']) || empty($params['career']))
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //TODO: validar formato, tipo, etc..
        $subject = new Subject();
        $subject->setName( $params['name'] );
        $subject->setShortName( $params['short_name'] );
        $subject->setDescription( $params['description'] );
        $subject->setSemester( $params['semester'] );
        $subject->setPlan( $params['plan'] );
        $subject->setCareer( $params['career'] );

        $req = $req->withAttribute('subject_data', $subject);

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
    public function checkData_period($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['start']) || !isset($params['end']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: start, end");

        if( empty($params['start']) || empty($params['end']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //TODO: verificar el formato de la fecha
        //TODO: verificar que no sea antes de NOW

        $period = new Period();
        $period->setDateStart( $params['start'] );
        $period->setDateEnd( $params['end'] );

        $req = $req->withAttribute('period_data', $period);

        $res = $next($req, $res);
        return $res;
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     *
     * @return Response
     */
    public function checkData_schedule_hours($req, $res, $next){

        $params = $req->getParsedBody();
        if( !isset($params['hours']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: hours");

        if( empty($params['hours']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Verificando que sean datos numericos
        $hours = $params['hours'];
        foreach ( $hours as $hour ){
            if( !is_numeric($hour) )
                return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");
        }

        $req = $req->withAttribute('schedule_hours', $hours);

        $res = $next($req, $res);
        return $res;
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     *
     * @return Response
     */
    public function checkData_schedule_subjects($req, $res, $next){

        $params = $req->getParsedBody();
        if( !isset($params['subjects']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros", "Se requiere: subjects");

        if( empty($params['subjects']) )
            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Verificando que sean datos numericos
        $subjects = $params['subjects'];
        foreach ( $subjects as $sub ){
            if( !is_numeric($sub) )
                return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");
        }

        $req = $req->withAttribute('schedule_subjects', $subjects);

        $res = $next($req, $res);
        return $res;
    }
}