<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Model\AdvisoryModel;
use App\Model\Subject;
use App\Service\AdvisoryService;
use App\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class AdvisoryController
{
    /**
     * @param $req Request
     * @param $res Response
     *
     * @return Response
     */
    public function getCurrentAdvisories($req, $res)
    {
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getCurrentAdvisories();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return mixed|Response
     */
    public function getAdvisory_ById($req, $res, $params){
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getAdvisory_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return mixed|Response
     */
    public function getAdvisoryHours_ById($req, $res, $params){
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getAdvisoryHours_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

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
    public function createStudentAdvisory($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            /* @var $advisory AdvisoryModel */
            $advisory = $req->getAttribute('advisory_data');
            //Se adiciona estudiante a objeto
            $advisory->setStudent( $params['id'] );
            $advisoryServ->insertAdvisory_CurrentPeriod( $advisory);
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Asesoria registrada con exito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     */
    public function updateAdvisory($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteAdvisory($req, $res){}


}