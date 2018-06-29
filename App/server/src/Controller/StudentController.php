<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
use App\Model\StudentModel;
use App\Service\AdvisoryService;
use App\Service\StudentService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class StudentController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function getStudents($req, $res)
    {
        try {
            $studentServ = new StudentService();
            $result = $studentServ->getStudents();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

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
    public function getStudent_ById($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $result = $studentSer->getStudent_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return Response
     */
    public function searchStudents($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $result = $studentSer->searchStudents_ByData( $params['search'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return Response
     * @throws \App\Exceptions\Persistence\TransactionException
     */
    public function updateStudent($req, $res, $params)
    {
        try{
            $studentService = new StudentService();
            /* @var $student StudentModel */
            $student = $req->getAttribute('student_data');
            $student->setId( $params['id'] );

            $email = $req->getAttribute('email_data');

            $studentService->updateStudent( $email, $student );
            return Utils::makeMessageResponse($res, Utils::$OK, "Estudiante actualizado con éxito");

        }catch (RequestException $e){
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteStudent($req, $res){}

    //--------------------
    // SCHEDULE
    //--------------------

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function getSchedule_ByStudentId($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $student_id = $params['id'];
            $result = $studentSer->getCurrentStudentSchedule_ById( $student_id );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

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
    public function createSchedule($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $studentSer->createSchedule( $params['id'] );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Horario de alumno creado");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    //-----------------------
    // ADVISORIES
    //-----------------------

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function getCurrentAdvisories_ByStudentId($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $student_id = $params['id'];
            $result = $studentSer->getCurrentAdvisories_ByStudentId( $student_id );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

//    /**
//     * @param $req Request
//     * @param $res Response
//     * @param $params array
//     * @return Response
//     */
//    public function createStudentAdvisory_CurrentPeriod($req, $res, $params)
//    {
//        try {
//            $studentSer = new StudentService();
//            $student_id = $params['id'];
//            /**@var $subject AdvisoryModel*/
//            $subject = $req->getAttribute('advisory_subject');
//            $subject->setStudent( $student_id );
//            $studentSer->createAdvisoryCurrentPeriod( $subject );
//            return Utils::makeMessageResponse( $res, Utils::$OK, "asesoría creada con éxito");
//
//        } catch (RequestException $e) {
//            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
//        }
//    }


    /**
     * @param $req Request
     * @param $res Response
     *
     * @param $params array
     *
     * @return Response
     */
    public function getCurrentAdvisories_Requested($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getCurrentAdvisories_Requested( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     *
     * @param $params array
     *
     * @return Response
     */
    public function getCurrentAdvisories_Adviser($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getCurrentAdvisories_Adviser( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }



}