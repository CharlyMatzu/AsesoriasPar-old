<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Objects\Period;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;
use Control\PeriodControl;

$app = new App;


//---------------------
// GET
//---------------------
//TODO: arreglar todas las funciones para que el status venga de Service
$app->get('/', function (Request $request, Response $response) {
    try{
        $control = new PeriodControl();
        $result = $control->getPeriods();
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


//$app->get('/{start}/{end}', function (Request $request, Response $response, $params) {
//    try{
//        $Service = new PeriodControl();
//        //TODO: verificar campos (vacios, corretos)
//        $result = $Service->getPeriods_ByDateRange( $params['start'], $params['end'] );
//
//        return $response->withJson( $result );
//
//    }catch (RequestException $ex){
//        return $response->withStatus( $ex->getRequestStatusCode() )
//            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
//    }
//});


//---------------------
// POST
//---------------------
$app->post('/create', function(Request $req, Response $res){
    //Se obtiene el json y se transforma en array
    $body = $req->getParsedBody();
    //Mando incormacion incorrecta
    if( $body == null )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new PeriodControl();
        //Registrando
        $result = $control->registerPeriod( $body['date_start'], $body['date_end'] );
        return $res->withStatus( Utils::$CREATED )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});



$app->delete('/', function(Request $req, Response $res) {
    $body = $req->getParsedBody();
    if( ($body == null) || ( !isset( $body['id'] )) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    $periodId = $body['id'];
    if( $periodId == null )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    try{
        $control = new PeriodControl();
        $result = $control->disablePeriod( $periodId );
        return $res->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});


$app->put('/', function(Request $req, Response $res) {
    $body = $req->getParsedBody();
    if( !isset($body['id']) || !isset($body['date_start']) || !isset($body['date_end']) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );


    $periodId = $body['id'];
    $periodStart = $body['date_start'];
    $periodEnd = $body['date_end'];
    if( ($periodId == null) || ($periodStart == null) || ($periodEnd == null) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    try{
        $control = new PeriodControl();
        $period = new Period();
        $period->setId( $periodId );
        $period->setDateStart( $periodStart );
        $period->setDateEnd( $periodEnd );

        $result = $control->updatePeriod( $period );
        return $res->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});




//----------------ERROR AN RUN
try{
    $app->run();
} catch (MethodNotAllowedException $e) {
    $res = Utils::makeJson( Utils::makeArrayResponse("Internal error", "Exception ocurred") );
    http_response_code( Utils::$INTERNAL_SERVER_ERROR);
    exit( $res );
} catch (NotFoundException $e) {
    $res = Utils::makeJson( Utils::makeArrayResponse("Internal error", "Exception ocurred") );
    http_response_code( Utils::$INTERNAL_SERVER_ERROR);
    exit( $res );
} catch (\Exception $e) {
    $res = Utils::makeJson( Utils::makeArrayResponse("Internal error", "Exception ocurred") );
    http_response_code( Utils::$INTERNAL_SERVER_ERROR);
    exit( $res );
}