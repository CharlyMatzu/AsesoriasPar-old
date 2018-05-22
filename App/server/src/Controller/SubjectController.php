<?php namespace Controller;

use Exceptions\RequestException;
use Model\Subject;
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
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Materias", $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Materia", $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Materia registrada con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return Response
     */
    public function updateSubject($req, $res, $params){
        try {
            $subjectService = new SubjectService();

            /* @var $subject Subject */
            $subject = $req->getAttribute('subject_data');
            $subject->setId( $params['id'] );

            $subjectService->updateSubject( $subject );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Materia actualizada");

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
    public function deleteSubject($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->changeStatus( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Materia eliminada con exito", $result );

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
    public function changeStatus($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $subjectService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Estado de materia modificado con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}