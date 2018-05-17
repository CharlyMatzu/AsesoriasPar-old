<?php namespace Controller;

use Exceptions\RequestException;
use Service\PlanService;
use Slim\Http\Request;
use Slim\Http\Response;
use Utils;

class PlanController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function getPlans($req, $res){
        try {
            $planService = new PlanService();
            $result = $planService->getPlans();
            return Utils::makeJSONResponse( $res, Utils::$OK, "Planes", $result );

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
    public function getPlan_ById($req, $res, $params){
        try {
            $planService = new PlanService();
            $result = $planService->getPlan_ById( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Plan", $result );

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     */
    public function createPlan($req, $res){
        try {
            $params = $req->getParsedBody();
            $planService = new PlanService();
            //$year = $req->getParam('plan_data');
            $planService->createPlan( $params['year'] );
            return Utils::makeJSONResponse( $res, Utils::$CREATED, "Plan registrado con éxito");

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
    public function updatePlan($req, $res, $params){
        try {
            $body = $req->getParsedBody();
            $planService = new PlanService();
            //$year = $req->getParam('plan_data');
            $planService->updatePlan( $params['id'], $body['year'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Plan actualizado con éxito");

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
    public function deletePlan($req, $res, $params){
        try {
            $planService = new PlanService();
            $planService->deletePlan( $params['id'] );
            return Utils::makeJSONResponse( $res, Utils::$OK, "Plan eliminado con éxito");

        } catch (RequestException $e) {
            return Utils::makeJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}