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


}