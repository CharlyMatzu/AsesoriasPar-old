<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
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
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
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
            $result = $scheduleService->getDaysAndHours();
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

    /**
     * @param $req Request
     * @param $res Response
     *
     * @param $params array
     *
     * @return Response
     */
    public function getScheduleMatchHours_ByStudents($req, $res, $params){
        try {
            $scheduleService = new ScheduleService();
            $result = $scheduleService->getCurrentScheduleMatch_ByStudentsId( $params['adviser'], $params['alumn'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result );

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }



//    /**
//     * @param $req Request
//     * @param $res Response
//     * @param $params array
//     * @return Response
//     */
//    public function addScheduleSubjects($req, $res, $params)
//    {
//        try {
//            $scheduleService = new ScheduleService();
//            $subjects = $req->getAttribute('schedule_subjects');
//            $scheduleService->insertScheduleSubjects( $params['id'], $subjects );
//            return Utils::makeMessageJSONResponse( $res, Utils::$CREATED, "Materias agregadas");
//
//        } catch (RequestException $e) {
//            return Utils::makeMessageJSONResponse( $res, $e->getStatusCode(), $e->getMessage() );
//        }
//    }


    /**
     * @param $req Request
     * @param $res Response
     * @param $params array
     *
     * @return Response
     * @throws \App\Exceptions\Persistence\TransactionException
     */
    public function updateScheduleHours($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $hours = $req->getAttribute('schedule_hours');
            $scheduleService->updateScheduleHours( $params['id'], $hours );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Horas actualizadas");

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
    public function getScheduleSubjects($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $result = $scheduleService->getScheduleSubjects_Byid( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

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
    public function getAvailableSubjects($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $result = $scheduleService->getAvailableSubjects_BySchedule( $params['id'] );
            return Utils::makeResultJSONResponse( $res, Utils::$OK, $result);

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
     * @throws \App\Exceptions\Persistence\TransactionException
     */
    public function updateScheduleSubjects($req, $res, $params)
    {
        try {
            $scheduleService = new ScheduleService();
            $subjects = $req->getAttribute('schedule_subjects');
            $scheduleService->updateScheduleSubjects( $params['id'], $subjects );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Materias actualizadas");

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
            $scheduleService = new ScheduleService();
            $scheduleService->changeStatus( $params['id'], $params['status'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Modificado estado de horario");

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
    public function validateScheduleSubject($req, $res, $params){
        try {
            $scheduleService = new ScheduleService();
            $scheduleService->validateScheduleSubject_BySchedule( $params['schedule'], $params['subject'], $params['status'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Modificado estado de horario");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }

}