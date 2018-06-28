<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Model\Period;
use App\Service\PeriodService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class PeriodController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Get all periods
     */
    public function getPeriods($req, $res){
        try {
            $periodService = new PeriodService();
            $result = $periodService->getPeriods();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );
        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Get current periods
     */
    public function getCurrentPeriod($req, $res){
        try {
            $periodService = new PeriodService();
            $result = $periodService->getCurrentPeriod();
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
     * Get period by ID
     */
    public function getPeriod_ById($req, $res, $params){
        try {
            $periodService = new PeriodService();
            $result = $periodService->getPeriod_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );
        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Add new period
     */
    public function createPeriod($req, $res){
        try {
            $periodService = new PeriodService();
            /* @var $period Period */
            $period = $req->getAttribute('period_data');
            $periodService->createPeriod( $period->getDateStart(), $period->getDateEnd() );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Periodo registrado con éxito");
        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     * Update period
     */
    public function updatePeriod($req, $res, $params){
        try {
            $periodService = new PeriodService();
            /* @var $period Period */
            $period = $req->getAttribute('period_data');
            $period->setId( $params['id'] );
            $periodService->updatePeriod( $period );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Periodo actualizado con éxito");
        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     * Change Status of periods
     */
    public function changeStatus($req, $res, $params){
        try {
            $periodService = new PeriodService();
            $periodService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Status de periodo modificado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }




    /**
     * @param $req Request
     * @param $res Response
     * @param $params
     * @return Response
     * Delete period by ID
     */
    public function deletePeriod($req, $res, $params){
        try {
            $periodService = new PeriodService();
            $periodService->deletePeriod( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Periodo eliminado");
        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }
}