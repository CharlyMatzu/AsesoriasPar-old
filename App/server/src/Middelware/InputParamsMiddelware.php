<?php namespace App\Middelware;


use App\Model\AdvisoryModel;
use App\Model\Career;
use App\Model\MailModel;
use App\Model\Period;
use App\Model\Student;
use App\Model\Subject;
use App\Model\User;
use Carbon\Carbon;
use PHPMailer\PHPMailer\Exception;
use Carbon\Exceptions\InvalidDateException;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

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
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

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
    public function checkParam_search_student($req, $res, $next)
    {
//        $student_search = $this->getRouteParams($req)['search_student'];

        //TODO: validar un poco

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
    public function checkParam_Advisory($req, $res, $next)
    {
        $advisory = $this->getRouteParams($req)['advisory'];
        //Verifica que sea un string numerico (no int porque viene como string)
        if( !is_numeric($advisory) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

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
    public function checkParam_Schedule($req, $res, $next)
    {
        $id = $this->getRouteParams($req)['schedule'];
        //Verifica que sea un string numerico (no int porque viene como string)
        if( !is_numeric($id) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

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
    public function checkParam_Status($req, $res, $next)
    {
        $status = $this->getRouteParams($req)['status'];
        //Verifica que sea un string numerico (no int porque viene como string)
        if( !is_numeric($status) || $status === "" || $status == null )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

        if( ($status != Utils::$STATUS_ENABLE)
            && ($status != Utils::$STATUS_DISABLE)
            //&& ($status != Utils::$STATUS_NO_CONFIRM)
        )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

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
    public function checkParam_Email($req, $res, $next)
    {
        $email = $this->getRouteParams($req)['email'];
        //Verifica que sea un string numerico (no int porque viene como string)
        if( empty($email) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

        //TODO: no debe contener caracteres extraños

        //Si no es un correo valido
//        if( !preg_match(Utils::EXPREG_EMAIL, $email) )
//            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametro invalido");

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
    public function checkData_Role($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['role']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros: Se requiere: role");

        if( empty($params['role']) || empty($params['password']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros de rol invalidos");

        //El tipo basic es solo con estudiante
        if( //$params['role'] != Utils::$ROLE_BASIC &&
            $params['role'] != Utils::$ROLE_MOD &&
            $params['role'] != Utils::$ROLE_ADMIN)
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros de rol invalidos");


        $req = $req->withAttribute('role_data', $params['role']);

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
    public function checkData_Mail($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['address']) || !isset($params['subject']) || !isset($params['body']) || !isset($params['plainBody']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros: Se requiere: address, subject, body, plainBody");

        if( empty($params['address']) || empty($params['subject']) || empty($params['body']) || empty($params['plainBody']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Campos vacios");

        if( !is_array($params['address']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Address debe ser array de correos");


        foreach ( $params['address'] as $address ){
            //Si esta vacio
            if( empty($address) )
                return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Address no valido: vacio");
            if( !is_string($address) )
                return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Address no valido: No es Email");
            //TODO: validar
//            if(  !preg_match(Utils::EXPREG_EMAIL, $address ) )
//                return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Address no valido: No es email valido");
        }


        $mail = new MailModel();
        $mail->setAddress( $params['address'] );
        $mail->setSubject( $params['subject'] );
        $mail->setBody( $params['body'] );
        $mail->setPlainBody( $params['plainBody'] );


        $req = $req->withAttribute('mail_data', $mail);

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
    public function checkData_User($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['email']) || !isset($params['password']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros: Se requiere: email, password");

        //FIXME: debe poder aceptar password vacio para el caso del update
        if( empty($params['email']) || empty($params['password']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        $email = $params['email'];
        $pass = $params['password'];

        //TODO validar
//        if( !preg_match(Utils::EXPREG_EMAIL, $email) ||
//            !preg_match(Utils::EXPREG_PASS, $pass) ||
//            !Utils::isRole($role) )
//            return Utils::makeJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Se crea objeto
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($pass);

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
    public function checkData_Student($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['first_name']) || !isset($params['last_name']) ||
            !isset($params['itson_id']) || !isset($params['phone']) ||
            !isset($params['career']))
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST,
                "Faltan parametros: Se requiere: first_name, last_name, itson_id, phone");

        if( empty($params['first_name']) || empty($params['last_name']) ||
            empty($params['itson_id']) || empty($params['phone']) ||
            empty($params['career']))
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

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
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, Se requiere: email, password");

        if( empty($params['email']) || empty($params['password']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

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
    public function checkData_Career($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['name']) || !isset($params['short_name']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, Se requiere: name, short_name");

        //TODO: podria dejarse abreviacion vacio
        if( empty($params['name']) || empty($params['short_name']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros vacios");

        $name = $params['name'];
        $short_name = $params['short_name'];


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
    public function checkData_Plan($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['year']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, Se requiere: year");

        if( empty($params['year']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos de plan: no debe estar vacio");

        if( ( !is_numeric($params['year']) ) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos de plan, debe ser numerico");

        if( ( strlen( $params['year'] ) != 4 ) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos de plan: deben ser 4 digitos");

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
    public function checkData_Subject($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['name']) || !isset($params['short_name']) || !isset($params['description']) ||
            !isset($params['semester']) || !isset($params['plan']) || !isset($params['career']))
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, 
            Se requiere: name, short_name, description, semester, plan, career");

        //Solo campos que se ocupan
        if( empty($params['name']) || empty($params['semester']) || empty($params['plan']) || empty($params['career']))
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Campos vacios");

        //TODO: validar formato, tipo, etc..
        if( !is_numeric($params['semester']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Semestre no es numerico");

        if( !is_numeric($params['plan']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Plan no es numerico");

        if( !is_numeric($params['career']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Carrera no es numerico");


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
    public function checkData_Period($req, $res, $next)
    {
        $params = $req->getParsedBody();
        if( !isset($params['start']) || !isset($params['end']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, Se requiere: start, end");

        if( empty($params['start']) || empty($params['end']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Campos vacios");

        //Formato de fecha invalido: aaaa/mm/dd
        if( !Utils::validateDateTime($params['start']) || !Utils::validateDateTime($params['end']))
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Fechas invalidas: aaaa/mm/dd");



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
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, Se requiere: hours");

        if( empty($params['hours']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        if( !is_array($params['hours']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Verificando que sean datos numericos
        $hours = $params['hours'];
        foreach ( $hours as $hour ){
            if( !is_numeric($hour) )
                return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");
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
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Faltan parametros, Se requiere: subjects");

        if( empty($params['subjects']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");


        if( !is_array($params['subjects']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");

        //Verificando que sean datos numericos
        $subjects = $params['subjects'];
        foreach ( $subjects as $sub ){
            if( !is_numeric($sub) )
                return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos");
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
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST,
                "Faltan parametros, Se requiere: subject, description");

        if( empty($params['subject']) || empty($params['description']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST, "Parametros invalidos: campos vacios");

        //no debe ser array
        if( is_array($params['subject']) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST,
                "Parametros invalidos: subject no debe ser array");

        //Verificando que sean datos numericos
        $subject = $params['subject'];
        if( !is_numeric($subject) )
            return Utils::makeMessageJSONResponse($res, Utils::$BAD_REQUEST,
                "Parametros invalidos: subject no es numerico");

        $advisory = new AdvisoryModel();
        $advisory->setSubject( $subject );
        $advisory->setDescription( $params['description'] );

        $req = $req->withAttribute('advisory_data', $advisory);

        $res = $next($req, $res);
        return $res;
    }



}