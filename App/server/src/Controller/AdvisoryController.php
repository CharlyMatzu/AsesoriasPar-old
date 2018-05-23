<?php namespace App\Controller;

use App\Exceptions\RequestException;
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
     */
    public function getAdvisory_ById($req, $res, $params){}


    /**
     * @param $req Request
     * @param $res Response
     */
    public function createAdvisory($req, $res){}


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