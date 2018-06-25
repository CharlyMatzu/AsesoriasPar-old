<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
use App\Model\CareerModel;
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
     */
    public function getCareers($req, $res)
    {
        try {
            $careerService = new CareerService();
            $result = $careerService->getCareers();
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
    public function getCareer_ById($req, $res, $params){
        try {
            $careerService = new CareerService();
            $result = $careerService->getCareer_ById( $params['id'] );
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
    public function getCareerSubjects($req, $res, $params){
        try {
            $careerService = new CareerService();
            $result = $careerService->getSubjects_ByCareer( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            /* @var $career CareerModel*/
            $career = $req->getAttribute('career_data');
            $careerService->insertCareers( $career->getName(), $career->getShortName() );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Carrera registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            /* @var $user CareerModel*/
            $user = $req->getAttribute('career_data');
            $user->setId( $params['id'] );
            $careerService->updateCarrers( $user );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Se actualizo carrera con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     */
    public function changeStatus($req, $res, $params){
        try {
            $careerService = new CareerService();
            $careerService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Se actualizo status con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            return Utils::makeMessageResponse( $res, Utils::$OK, "Carrera eliminada");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}