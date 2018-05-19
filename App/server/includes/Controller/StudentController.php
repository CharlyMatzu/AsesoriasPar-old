<?php namespace Controller;

use Exceptions\RequestException;
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
            return Utils::makeJSONResponse( $res, Utils::$OK, "Estudiantes", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeJSONResponse( $res, Utils::$OK, "Usuario encontrado", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     */
    public function updateStudent($req, $res){}


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
            return Utils::makeJSONResponse( $res, Utils::$OK, "Horario de alumno", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Horario de alumno creado");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            $studentSer->addScheduleSubjects( $params['id'], $subjects );
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Materias agregadas");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}