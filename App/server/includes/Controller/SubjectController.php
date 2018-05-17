<?php namespace Controller;

use Exceptions\RequestException;
use Service\SubjectService;
use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

class SubjectController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function getSubjects($req, $res){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getSubjects();
            return Utils::makeJSONResponse( $res, Utils::$OK, "Materias", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return Response
     */
    public function getSubject_ById($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getSubject_ById( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Materia", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function createSubject($req, $res){
        try {
            $subjectService = new SubjectService();
            $subject = $req->getAttribute('subject_data');
            $subjectService->insertSubject( $subject );
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Materia registrada con exito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     */
    public function updateSubject($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteSubject($req, $res){}


}