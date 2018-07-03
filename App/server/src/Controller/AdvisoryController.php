<?php namespace App\Controller;

use App\Exceptions\Request\RequestException;
use App\Model\AdvisoryModel;
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
    public function getAdvisories($req, $res)
    {
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getCurrentAdvisories();
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
     * @return mixed|Response
     */
    public function getAdvisory_ById($req, $res, $params){
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getAdvisory_ById( $params['id'] );
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
     * @return mixed|Response
     */
    public function getAdvisoryHours_ById($req, $res, $params){
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getAdvisorySchedule_ById( $params['id'] );
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
    public function createStudentAdvisory($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            /* @var $advisory AdvisoryModel */
            $advisory = $req->getAttribute('advisory_data');
            //Se adiciona estudiante a objeto. TODO: no mandar objeto....
            $advisory->setStudent( $params['id'] );
            $advisoryServ->insertAdvisory_CurrentPeriod( $advisory );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "asesoría registrada con éxito");

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
    public function assignAdviser($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            /* @var $advisory AdvisoryModel */
            $advisory = $req->getAttribute('advisory_schedule_data');
            //Se adiciona estudiante a objeto
//            $advisory->setStudent( $params['id'] );
            $advisoryServ->assignAdviser( $params['id'], $advisory->getAdviser(), $advisory->getSchedule() );
            return Utils::makeMessageResponse( $res, Utils::$CREATED, "Asignación de asesor con éxito");

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
    public function getAdvisorySchedule($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            $result = $advisoryServ->getAdvisorySchedule( $params['id'] );
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
    public function finalizeAdvisory($req, $res, $params)
    {
        try {
            $advisoryServ = new AdvisoryService();
            $advisoryServ->finalizeAdvisory( $params['id'] );
            return Utils::makeMessageResponse( $res, Utils::$OK, "Finalizado con éxito");

        } catch (RequestException $e) {
            return Utils::makeMessageResponse( $res, $e->getStatusCode(), $e->getMessage() );
        }
    }


    /**
     * @param $req Request
     * @param $res Response
     */
    public function deleteAdvisory($req, $res){}


}