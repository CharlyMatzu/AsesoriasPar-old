<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Service\PlanService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class PlanController
{
    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Get all plans
     */
    public function getPlans($req, $res){
        try {
            $planService = new PlanService();
            $result = $planService->getPlans();
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
     * Get plan by ID
     */
    public function getPlan_ById($req, $res, $params){
        try {
            $planService = new PlanService();
            $result = $planService->getPlan_ById( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @return Response
     * Add new Plan 
     */
    public function createPlan($req, $res){
        try {
            $params = $req->getParsedBody();
            $planService = new PlanService();
            //$year = $req->getParam('plan_data');
            $planService->createPlan( $params['year'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Plan registrado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Update plab by ID
     * @return Response
     */
    public function updatePlan($req, $res, $params){
        try {
            $body = $req->getParsedBody();
            $planService = new PlanService();
            //$year = $req->getParam('plan_data');
            $planService->updatePlan( $params['id'], $body['year'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Plan actualizado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Delete plan by ID
     * @return Response
     */
    public function deletePlan($req, $res, $params){
        try {
            $planService = new PlanService();
            $planService->deletePlan( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Plan eliminado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * Change status plan by ID
     * @return Response
     */
    public function changeStatus($req, $res, $params){
        try {
            $planService = new PlanService();
            $planService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Status modificado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}