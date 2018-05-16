<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Model\Career;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;
use Control\CareerControl;

$app = new App;


$app->get('/', function(Request $request, Response $response, $params){
    try{
        $control = new CareerControl();
        $result = $control->getCareers();
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});

$app->get('/{name}', function(Request $request, Response $response, $params){
   try{
       $control = new CareerControl();
       $result = $control->getCareer_ByName( $params['name'] );
       return $response->withStatus( Utils::$OK )->withJson( $result );

   }catch (RequestException $ex){
       return $response->withStatus( $ex->getStatusCode() )
           ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
   }
});


$app->post('/', function (Request $req, Response $res) {

    //Se obtiene el json y se transforma en array
    $body = $req->getParsedBody();
    //Mando incormacion incorrecta
    if( $body == null )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    try{
       //Obteniendo datos
        $control = new CareerControl();
        //TODO: validar que vengan los campos requeridos y el formato antes de enviar
        //Registrando
        $result = $control->insertCareers( $body['name'], $body['short_name'] );
        return $res->withStatus( Utils::$CREATED )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});



$app->put('/', function (Request $req, Response $res) {
    $body = $req->getParsedBody();
    if( !isset($body['id']) || !isset($body['name']) || !isset($body['short_name']) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    //obtenemos variables
    $id = $body['id'];
    $name = $body['name'];
    $short_name = $body['short_name'];

    if( ($id == null) || ($name == null) || ($short_name == null) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );


try{
    //Insertamos career
    $career = new Career();
    $career->setId($id);
    $career->setName($name);
    $career->setShortName($short_name);

    $control = new CareerControl();
    $result = $control->updateCarrers($career);

    return $res->withStatus( Utils::$OK )->withJson( $result );

}catch (RequestException $ex){
    return $res->withStatus( $ex->getStatusCode() )
         ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});



$app->delete('/', function (Request $req, Response $res) {

    //TODO: Validar campos y valores
    $body = $req->getParsedBody();
    if( ($body == null) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    $careerID = $body['id'];
    if( $careerID == null )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    try{
        $control = new CareerControl();

        $result = $control->disableCareer($careerID);
        return $res->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});
/*
$app->post('/search', function (Request $request, Response $response) {
    $Service = new CareerControl();

    $param = $request->getParsedBody();
    //Obtenemos json
    $json = $param['json_career'];
    //Decodificamos y hacemos array asociativo
    $careerArray = json_decode($json, true);
    //obtenemos variables
    $toSearch = $careerArray['toSearch'];
    $value = $careerArray['value'];


    $res = $Service->searchCareers($toSearch,$value);

    $mensaje = $res['message'];

    if ($res['result'] == true)
        return $response->withJson(Utils::makeArrayResponse($mensaje, ""));
    else if ($res['result'] == false)
        return $response->withStatus(500)->withJson( Utils::makeArrayResponse($mensaje, "") );
    else if ($res['result'] == null)
        return $response->withStatus(200)->withJson( Utils::makeArrayResponse($mensaje, "") );
});
*/

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