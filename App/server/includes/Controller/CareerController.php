<?php namespace Controller;

use Exceptions\RequestException;
use Model\Career;
use Service\CareerService;
use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

class CareerController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function getCareers($req, $res)
    {
        try {
            $careerService = new CareerService();
            $result = $careerService->getCareers();
            return Utils::makeJSONResponse( $res, Utils::$OK, "Carreras", $result );

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
    public function getCareer_ById($req, $res, $params){
        try {
            $careerService = new CareerService();
            $result = $careerService->getCareer_ById( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Carrera", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response

     */
    public function createCareer($req, $res){
        try {
            $careerService = new CareerService();
            /* @var $career Career*/
            $career = $req->getAttribute('career_data');
            $careerService->insertCareers( $career->getName(), $career->getShortName() );
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Carrera registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     *
     * @return Response
     */
    public function updateCareer($req, $res, $params){
        try {
            $careerService = new CareerService();
            /* @var $user Career*/
            $user = $req->getAttribute('career_data');
            $user->setId( $params['id'] );
            $careerService->updateCarrers( $user );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Se actualizo carrera con exito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     */
    public function disableCareer($req, $res, $params){
        try {
            $careerService = new CareerService();
            $careerService->disableCareer( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Carrera deshabilitada");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     */
    public function deleteCareer($req, $res, $params){
        try {
            $careerService = new CareerService();
            $careerService->deleteCareer( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Carrera eliminada");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}