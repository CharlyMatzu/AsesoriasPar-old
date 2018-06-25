<?php namespace App\Middleware;



use App\Model\AdvisoryModel;
use App\Model\CareerModel;
use App\Model\PeriodModel;
use App\Model\StudentModel;
use App\Model\SubjectModel;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class InputParamsMiddleware extends Middleware
{

    //Cuando es un valor por GET, el método debe llamarse checkParam_NombreParámetro[s]
    //Cuando es un valor mediante POST o similar, el método debe llamarse checkData_Nombre



    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkParam_Id($req, $res, $next)
    {
        $id = self::getRouteParams($req)['id'];
        //Verifica que sea un string numérico (no int porque viene como string)
        if( !is_numeric($id) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "parámetros invalido");

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkParam_Search($req, $res, $next)
    {

        $search = self::getRouteParams($req)['search'];

        if( !preg_match(Utils::EXPREG_SEARCH, $search) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos: no es un email valido");

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkParam_Advisory($req, $res, $next)
    {
        $advisory = self::getRouteParams($req)['advisory'];
        //Verifica que sea un string numérico (no int porque viene como string)
        if( !is_numeric($advisory) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "parámetros invalido");

        $res = $next($req, $res);
        return $res;
    }

    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkParam_Schedule($req, $res, $next)
    {
        $id = self::getRouteParams($req)['schedule'];
        //Verifica que sea un string numérico (no int porque viene como string)
        if( !is_numeric($id) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "parámetros invalido");

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkParam_Status($req, $res, $next)
    {
        $status = self::getRouteParams($req)['status'];

        //Verifica los status disponibles
        if( ($status != Utils::$STATUS_ACTIVE)
            && ($status != Utils::$STATUS_DISABLE)
            && ($status != Utils::$STATUS_PENDING)
            && ($status != Utils::$STATUS_FINALIZED)
            && ($status != Utils::$STATUS_VALIDATED)
            && ($status != Utils::$STATUS_LOCKED)
        )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Debe ser: ACTIVE, DISABLED, PENDING, FINALIZED, VALIDATE, LOCKED");

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Role($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['role']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros: Se requiere: role");

        if( empty($params['role']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros de rol invalidos: Campos vacíos");

        //El tipo basic es solo con estudiante
        if( //$params['role'] != Utils::$ROLE_BASIC &&
            $params['role'] != Utils::$ROLE_MOD &&
            $params['role'] != Utils::$ROLE_ADMIN)
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros de rol invalidos: moderator o administrator");


        $req = $req->withAttribute('role_data', $params['role']);

        $res = $next($req, $res);
        return $res;
    }



    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Email($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['email']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros: Se requiere email");

        if( empty($params['email']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos: email vacío");

        $email = $params['email'];

        if( !preg_match(Utils::EXPREG_EMAIL, $email) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos: no es un email valido");


        //Se envían los Parámetros mediante el request ya validados
        $req = $req->withAttribute('email_data', $email);

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Password($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['password']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros: Se requiere password");


        if( empty($params['password']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos: password vacío");

        $pass = $params['password'];

        //FIXME: deja pasar
        if( !preg_match(Utils::EXPREG_PASS, $pass) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos: no es un password valido");


        //Se envían los Parámetros mediante el request ya validados
        $req = $req->withAttribute('password_data', $pass);

        $res = $next($req, $res);
        return $res;
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Student($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['first_name']) || !isset($params['last_name']) ||
            !isset($params['itson_id']) || !isset($params['phone']) ||
            !isset($params['career']))
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST,
                "Faltan Parámetros: Se requiere: first_name, last_name, itson_id, phone");

        if( empty($params['first_name']) || empty($params['last_name']) ||
            empty($params['itson_id']) || empty($params['phone']) ||
            empty($params['career']))
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos");

        $first = $params['first_name'];
        $last = $params['last_name'];
        $itson = $params['itson_id'];
        $phone = $params['phone'];
        $career = $params['career'];


        //Se crea objeto estudiante
        $student = new StudentModel();
        //Se agregan datos
        $student->setFirstName($first);
        $student->setLastName($last);
        $student->setItsonId($itson);
        $student->setPhone($phone);
        $student->setCareer($career);


        //Se envían los Parámetros mediante el request ya validados
        $req = $req->withAttribute('student_data', $student);

        $res = $next($req, $res);
        return $res;
    }



    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Career($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['name']) || !isset($params['short_name']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros, Se requiere: name, short_name");

        //TODO: podria dejarse abreviacion vacío
        if( empty($params['name']) || empty($params['short_name']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros vacios");

        $name = $params['name'];
        $short_name = $params['short_name'];


        //Se crea objeto
        $career = new CareerModel();
        $career->setName( $name );
        $career->setShortName( $short_name );

        //Se envian los Parámetros mediante el request ya validados
        $req = $req->withAttribute('career_data', $career);

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Plan($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['year']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros, Se requiere: year");

        if( empty($params['year']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos de plan: no debe estar vacío");

        if( ( !is_numeric($params['year']) ) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos de plan, debe ser numérico");

        if( ( strlen( $params['year'] ) != 4 ) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos de plan: deben ser 4 digitos");

        $res = $next($req, $res);
        return $res;
    }


    /**
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Subject($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['name']) || !isset($params['short_name']) || !isset($params['description']) ||
            !isset($params['semester']) || !isset($params['plan']) || !isset($params['career']))
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan parámetros, 
            Se requiere: name, short_name, description, semester, plan, career");

        //Solo campos que se ocupan
        if( empty($params['name']) || empty($params['semester']) || empty($params['plan']) || empty($params['career']))
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Campos vacíos");

        //TODO: validar formato, tipo, etc..
        if( !is_numeric($params['semester']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Semestre no es numérico");

        if( !is_numeric($params['plan']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Plan no es numérico");

        if( !is_numeric($params['career']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Carrera no es numérico");


        $subject = new SubjectModel();
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
     * Verifica que el parámetros enviado sea un valor valido
     * @param $req Request
     * @param $res Response
     * @param $next callable
     * @return Response
     */
    public function checkData_Period($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['start']) || !isset($params['end']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros, Se requiere: start, end");

        if( empty($params['start']) || empty($params['end']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Campos vacios");

        //Formato de fecha invalido: aaaa/mm/dd
//        if( !Utils::validateDateTime($params['start']) || !Utils::validateDateTime($params['end']))
//            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Fechas invalidas: aaaa/mm/dd");



        //TODO: verificar el formato de la fecha
        //TODO: verificar que no sea antes de NOW

        $period = new PeriodModel();
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
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros, Se requiere: hours");

//        if( empty($params['hours']) )
//            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos");

        if( !is_array($params['hours']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Debe ser un array");

        //Verificando que sean datos numericos
        $hours = $params['hours'];
        foreach ( $hours as $hour ){
            if( !is_numeric($hour) )
                return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Valores deben ser numericos");
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
    public function checkData_advisory_schedule($req, $res, $next)
    {

        $params = $req->getParsedBody();
        if ( !isset($params['hours']) || !isset($params['adviser']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros, Se requiere: hours, adviser");

        if( empty($params['hours']) || empty($params['adviser']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros vacios");

        if (!is_array($params['hours']))
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Hours debe ser array");

        //Verificando que sean datos numericos
        $hours = $params['hours'];
        foreach ($hours as $hour) {
            if (!is_numeric($hour))
                return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Valores de hours deben ser numericos");
        }

        $advisory = new AdvisoryModel();
        $advisory->setSchedule( $hours );
        $advisory->setAdviser( $params['adviser'] );

        $req = $req->withAttribute('advisory_schedule_data', $advisory);

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
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Faltan Parámetros, Se requiere: subjects");

//        if( empty($params['subjects']) )
//            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Subjects vacío");


        if( !is_array($params['subjects']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "No es un array");

        //Verificando que sean datos numericos
        $subjects = $params['subjects'];
        foreach ( $subjects as $sub ){
            if( !is_numeric($sub) )
                return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Valores no son numericos");
        }

        $req = $req->withAttribute('schedule_subjects', $subjects);

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
    public function checkData_advisory($req, $res, $next){

        $params = $req->getParsedBody();
        if( !isset($params['subject']) || !isset($params['description']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST,
                "Faltan Parámetros, Se requiere: subject, description");

        if( empty($params['subject']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST, "Parámetros invalidos: campos vacios");

        //Si esta vacío, se le pone un texto por default
        if( empty($params['description']) )
            $params['description'] = "Sin descripción";

        //no debe ser array
        if( is_array($params['subject']) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST,
                "Parámetros invalidos: subject no debe ser array");

        //Verificando que sean datos numericos
        $subject = $params['subject'];
        if( !is_numeric($subject) )
            return Utils::makeMessageResponse($res, Utils::$BAD_REQUEST,
                "Parámetros invalidos: subject no es numérico");

        $advisory = new AdvisoryModel();
        $advisory->setSubject( $subject );
        $advisory->setDescription( $params['description'] );

        $req = $req->withAttribute('advisory_data', $advisory);

        $res = $next($req, $res);
        return $res;
    }



}