<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
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
     */
    public function getPlans($req, $res){
        try {
            $planService = new PlanService();
            $result = $planService->getPlans();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
    public function createPlan($req, $res){
        try {
            $params = $req->getParsedBody();
            $planService = new PlanService();
            //$year = $req->getParam('plan_data');
            $planService->createPlan( $params['year'] );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Plan registrado con éxito");

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
    public function updatePlan($req, $res, $params){
        try {
            $body = $req->getParsedBody();
            $planService = new PlanService();
            //$year = $req->getParam('plan_data');
            $planService->updatePlan( $params['id'], $body['year'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Plan actualizado con éxito");

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
    public function deletePlan($req, $res, $params){
        try {
            $planService = new PlanService();
            $planService->deletePlan( $params['id'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Plan eliminado con éxito");

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
    public function changeStatus($req, $res, $params){
        try {
            $planService = new PlanService();
            $planService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Status modificado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


}