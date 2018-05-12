<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Objects\Subject;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;
use Control\SubjectControl;

$app = new App;


$app->get('/', function (Request $request, Response $response) {

    try{
        $control = new SubjectControl();
        $result = $control->getSubjects();
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});

$app->get('/{name}', function (Request $request, Response $response, $params) {

    try{
        $control = new SubjectControl();
        $result = $control->getSubjects_ByName( $params['name'] );
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


//$app->get('/{career}', function (Request $request, Response $response, $params) {
//
//    $name = $params['name'];
//
//    try{
//        $control = new SubjectControl();
//        $result = $control->getSubjects_ByName( $name );
//        return $response->withStatus( Utils::$OK )->withJson( $result );
//
//    }catch (RequestException $ex){
//        return $response->withStatus( $ex->getRequestStatusCode() )
//            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
//    }
//});



$app->post('/', function (Request $req, Response $res) {

    //Se obtiene el json y se transforma en array
    $body = $req->getParsedBody();
    //Mando incormacion incorrecta
    if( $body == null )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );
    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new SubjectControl();
        $subject = new Subject();
            $subject->setName($body['name']);
            $subject->setShortName($body['short_name']);
            $subject->setDescription($body['description']);
            $subject->setSemester($body['semester']);
            $subject->setPlan($body['plan']);
            $subject->setCareer($body['career']);

        //Registrando
        $result = $control->registerSubject( $subject );
        return $res->withStatus( Utils::$CREATED )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});

//---------------MATERIAS RELACIONADAS
$app->post('/relation', function(Request $request, Response $response){
    $body = $request->getParsedBody();
    //TODO: verificar campos

    try{

        $control = new SubjectControl();
        $result = $control->addSimilarySubjetcs( $body['main'], $body['subjects'] );
        return $response->withStatus( Utils::$CREATED )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});


$app->delete('/', function (Request $req, Response $res) {

    $body = $req->getParsedBody();
    if( ($body == null) || ( !isset( $body['id'] )) )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    $subjectID = $body['id'];
    if( $subjectID == null )
        return $res->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    try{
        $control = new SubjectControl();

        $result = $control->disableSubject($subjectID);
        return $res->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $res->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});

$app->put('/', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
    if( !isset($body['id']) || !isset($body['name']) || !isset($body['short_name'])|| !isset($body['description'])|| !isset($body['semester'])|| !isset($body['plan'])|| !isset($body['career']) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );

    //obtenemos variables
    $id = $body['id'];
    $name = $body['name'];
    $short_name = $body['short_name'];
    $description = $body['description'];
    $semester = $body['semester'];
    $plan = $body['plan'];
    $career = $body['career'];

    if( ($id == null) || ($name == null) || ($short_name == null) || ($description == null)|| ($semester == null)|| ($plan == null)|| ($career == null))
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );


    try{
        //Insertamos career
        $subject = new Subject();
        $subject->setId($id);
        $subject->setName($name);
        $subject->setShortName($short_name);
        $subject->setDescription($description);
        $subject->setSemester($semester);
        $subject->setCareer($career);
        $subject->setPlan($plan);

        $control = new SubjectControl();

        $result = $control->updateSubject($subject);
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});




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
