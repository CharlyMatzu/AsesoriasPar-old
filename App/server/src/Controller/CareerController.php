<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Model\Career;
use App\Service\CareerService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class CareerController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Get all careers 
     */
    public function getCareers($req, $res)
    {
        try {
            $careerService = new CareerService();
            $result = $careerService->getCareers();
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
     * Get career by Id
     */
    public function getCareer_ById($req, $res, $params){
        try {
            $careerService = new CareerService();
            $result = $careerService->getCareer_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Add new Career
     */
    public function createCareer($req, $res){
        try {
            $careerService = new CareerService();
            /* @var $career Career*/
            $career = $req->getAttribute('career_data');
            $careerService->insertCareers( $career->getName(), $career->getShortName() );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Carrera registrada con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * Update a career with the ID of parameter
     * @return Response
     */
    public function updateCareer($req, $res, $params){
        try {
            $careerService = new CareerService();
            /* @var $user Career*/
            $user = $req->getAttribute('career_data');
            $user->setId( $params['id'] );
            $careerService->updateCarrers( $user );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Se actualizo carrera con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     * Change status of career with the ID of Parameter
     */
    public function changeStatus($req, $res, $params){
        try {
            $careerService = new CareerService();
            if( $params['status'] == Utils::$STATUS_DISABLE ){
                $careerService->disableCareer( $params['id'] );
                return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Desactivado con éxito");
            }
            else if( $params['status'] == Utils::$STATUS_ENABLE ){
                $careerService->enableCareer( $params['id'] );
                return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Activado con éxito");
            }


        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     * Delete career with the ID of parameter
     */
    public function deleteCareer($req, $res, $params){
        try {
            $careerService = new CareerService();
            $careerService->deleteCareer( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Carrera eliminada");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }
}