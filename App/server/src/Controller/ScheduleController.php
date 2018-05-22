<?php namespace App\Controller;

use App\Exceptions\RequestException;
use App\Service\ScheduleService;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils;

class ScheduleController
{
    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     * @return Response
     */
    public function getSchedule_ById($req, $res, $params){
        try {
            $scheduleService = new ScheduleService();
            $result = $scheduleService->getSchedule_ById( $params['id'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Horario", $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req
     * @param $res
     *
     * @return Response
     */
    public function getHoursAndDays($req, $res){
        try {
            $scheduleService = new ScheduleService();
            $result = $scheduleService->getHoursAndDays();
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Horas y dias disponibles", $result );

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }



    /**
     * @param $req Request
     * @param $res Response
     */
    public function createSchedule($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function updateSchedule($req, $res){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteSchedule($req, $res){}


}