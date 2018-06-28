<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Model\Student;
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
     * Get all students
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
     * Get student by ID
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
     * @param $params array
     * @return Response
     * Search student by any text student name content
     */
    public function searchStudents($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $result = $studentSer->searchStudents_ByData( $params['search_student'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }



    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Update students by ID
     * @return Response
     */
    public function updateStudent($req, $res, $params)
    {
        try{
            $studentService = new StudentService();
            /* @var $student Student */
            $student = $req->getAttribute('student_data');
            $student->setId( $params['id'] );
            $studentService->updateStudent( $student );
            return Utils::makeMessageJSONResponse($res, Utils::$OK, "Estudiante actualizado con éxito");

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
     * Get current schedule by student ID
     */
    public function getCurrentSchedule_ByStudentId($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $student_id = $params['id'];
            $result = $studentSer->getCurrentStudentSchedule_ById( $student_id );
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
     * Add a new schedule of student
     */
    public function createSchedule($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
//            $hours = $req->getAttribute('schedule_hours');
            $studentSer->createSchedule( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Horario de alumno creado");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
     * Get current advisaries by student ID
     */
    public function getCurrentAdvisories_ByStudentId($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $student_id = $params['id'];
            $result = $studentSer->getCurrentAdvisories_ByStudentId( $student_id );
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
     * Add new advisory in current period
     */
    public function createStudentAdvisory_CurrentPeriod($req, $res, $params)
    {
        try {
            $studentSer = new StudentService();
            $student_id = $params['id'];
            $subject = $req->getAttribute('advisory_subject');
            $studentSer->createAdvisoryCurrentPeriod( $student_id,  $subject);
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Asesoria creada con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }
}