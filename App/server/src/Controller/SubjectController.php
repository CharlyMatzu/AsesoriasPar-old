<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Model\Subject;
use App\Service\SubjectService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class SubjectController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Get all subjects
     */
    public function getSubjects($req, $res){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getSubjects();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Get all subjects enable
     */
    public function getEnabledSubjects($req, $res){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getEnabledSubjects();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Get subject by ID
     * @return Response
     */
    public function getSubject_ById($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getSubject_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }
    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Search subjects by career, semester, plan
     * @return Response
     */
    public function getSubject_Search($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getSubject_SearchFilter( $params['career'],$params['semester'],$params['plan'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );
            

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }
/**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Search subjects by Name
     * @return Response
     */
    public function searchSubjects_ByName($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->searchSubjects_ByName( $params['name'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Get current advisory by subject id
     * @return Response
     */
    public function getCurrentAdvisers_BySubject($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $result = $subjectService->getCurrentAdvisers_BySubject( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Add new subject
     */
    public function createSubject($req, $res){
        try {
            $subjectService = new SubjectService();
            $subject = $req->getAttribute('subject_data');
            $subjectService->insertSubject( $subject );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Materia registrada con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Update subjects by ID
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
     * Delete subjects by ID
     */
    public function deleteSubject($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $subjectService->deleteSubject( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Materia eliminada con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     * Change status subjects
     */
    public function changeStatus($req, $res, $params){
        try {
            $subjectService = new SubjectService();
            $subjectService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Estado de materia modificado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}