<?php namespace Controller;

use Exceptions\RequestException;
use Model\Student;
use Service\StudentService;
use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

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
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function updateStudent($req, $res)
    {
        try{
            $studentService = new StudentService();
            /* @var $student Student */
            $student = $req->getAttribute('student_data');
            $studentService->updateStudent( $student );
            return Utils::makeMessageJSONResponse($res, Utils::$OK, "Estudiante actualizado con exito");

        }catch (RequestException $e){
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
    public function getCurrentStudentSchedule_ById($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $student_id = $params['id'];
            $result = $studentSer->getCurrentSchedule( $student_id );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

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
    public function createSchedule($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $hours = $req->getAttribute('schedule_hours');
            $studentSer->createSchedule( $params['id'], $hours );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Horario de alumno creado");

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
    public function addScheduleSubjects($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $subjects = $req->getAttribute('schedule_subjects');
            $studentSer->addScheduleSubjects_current( $params['id'], $subjects );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Materias agregadas");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}