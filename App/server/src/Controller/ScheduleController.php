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
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

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
     */
    public function addScheduleSubjects($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $subjects = $req->getAttribute('schedule_subjects');
            $scheduleService->insertScheduleSubjects( $params['id'], $subjects );
            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Materias agregadas");

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
    public function updateScheduleHours($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $hours = $req->getAttribute('schedule_hours');
            $scheduleService->updateScheduleHours( $params['id'], $hours );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Horas actualizadas");

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
    public function updateScheduleSubjects($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $subjects = $req->getAttribute('schedule_subjects');
            $scheduleService->updateScheduleSubjects( $params['id'], $subjects );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Materias actualizadas");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }



    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return Response
     */
    public function changeScheduleStatus($req, $res, $params){
        try {
            $scheduleService = new ScheduleService();
            $scheduleService->changeSchedyleStatus( $params['id'], $params['status'] );
            return Utils::makeMessageJSONResponse( $res, Utils::$OK, "Modificaco estado de horario");

        } catch (RequestException $e) {
            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteSchedule($req, $res){}


}