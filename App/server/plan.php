<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;
use Control\PlanControl;

$app = new App;

//---------------------
// GET
//---------------------

//TODO: arreglar todas las funciones para que el status venga de control
$app->get('/', function (Request $request, Response $response) {

    try{
        $control = new PlanControl();
        $result = $control->getPlans();
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});

//
////TODO: arreglar todas las funciones para que el status venga de control
//$app->get('/{year}', function (Request $request, Response $response, $params) {
//    $control = new PlanControl();
//    if( !isset($params['year']) )
//        return $response->withStatus( Utils::$BAD_REQUEST )
//            ->withJson( Utils::makeArrayResponse( "Parametros incorrectos" ) );
//
//     $year = $params['year'];
//    if( $params['year'] == null )
//        return $response->withStatus( Utils::$BAD_REQUEST )
//            ->withJson( Utils::makeArrayResponse( "Campo vacio" ) );
//
//    $result = $control->getPlan_ByYear($year);
//    return $response->withStatus( $result['result'] )
//        ->withJson( Utils::makeArrayResponse( $result['message'], $result['data'] ) );
//});



//---------------------
// POST
//---------------------
$app->post('/', function(Request $request, Response $response){
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( $body == null || !isset($body['year']))
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    if( $body['year'] == null )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    try{
        //Obteniendo datos
        //TODO: validar formato
        $control = new PlanControl();
        //Registrando
        $result = $control->registerPlan( $body['year'] );
        return $response->withStatus( Utils::$CREATED )->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});



$app->put('/', function(Request $request, Response $response){
    //Se obtiene el json y se transforma en array
    //TODO: comprobar JSON correcto
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( $body == null)
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    if( !isset($body['id']) || !isset($body['year']) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    try{
        //Obteniendo datos
        //TODO: validar formato de año
        $control = new PlanControl();
        //Registrando
        $result = $control->updatePlan( $body['id'], $body['year'] );
        return $response->withStatus( Utils::$OK )->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


//TODO: implementar metodo (se requiere status para el plan en la DB)
//$app->delete('/', function(Request $req, Response $res){
//    //Se obtiene el json y se transforma en array
//    //TODO: comprobar JSON correcto
//    $body = $req->getParsedBody();
//    //Mando incormacion incorrecta
//    if( $body == null)
//        return $res->withStatus(Utils::$BAD_REQUEST)
//            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
//
//    if( !isset($body['id']) )
//        return $res->withStatus(Utils::$BAD_REQUEST)
//            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
//
//
//    try{
//        //Obteniendo datos
//        //TODO: validar formato de año
//        $control = new PlanControl();
//        //Registrando
//        $result = $control->disablePlan( $body['id'] );
//        return $res->withStatus( Utils::$OK )->withJson( $result );
//    }catch (RequestException $ex){
//        return $res->withStatus( $ex->getRequestStatusCode() )
//            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
//    }
//});






//----------------ERROR AN RUN
try{
    $app->run();
} catch (MethodNotAllowedException $e) {
    $res = Utils::makeJson( Utils::makeArrayResponse("Internal error", "Exception ocurred") );
    http_response_code( Utils::$INTERNAL_ERROR_REQUEST);
    exit( $res );
} catch (NotFoundException $e) {
    $res = Utils::makeJson( Utils::makeArrayResponse("Internal error", "Exception ocurred") );
    http_response_code( Utils::$INTERNAL_ERROR_REQUEST);
    exit( $res );
} catch (\Exception $e) {
    $res = Utils::makeJson( Utils::makeArrayResponse("Internal error", "Exception ocurred") );
    http_response_code( Utils::$INTERNAL_ERROR_REQUEST);
    exit( $res );
}

