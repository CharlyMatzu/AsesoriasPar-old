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
     */
    public function getPlan_ById($req, $res, $params){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function createPlan($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function updatePlan($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deletePlan($req, $res){}


}