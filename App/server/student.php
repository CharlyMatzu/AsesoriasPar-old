<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Objects\Student;
use Control\StudentControl;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use Utils;
use \Slim\App;

use Control\UserControl;

$app = new App;

//TODO: arreglar todas las funciones para que el status venga de control
$app->get('/', function (Request $request, Response $response) {
    try{
        $control = new StudentControl();
        $result = $control->getStudents();
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});

$app->post('/create', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['itson_id'])) || (!isset($body['first_name'])) || (!isset($body['last_name'])) || (!isset($body['user'])) || (!isset($body['career'])) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new StudentControl();
        //Registrando
        $itson_id = $body['itson_id'];
        $firstName = $body['first_name'];
        $lastName = $body['last_name'];
        $user = $body['user'];
        $career = $body['career'];

        if( ($itson_id == null) || ($firstName == null) || ($lastName == null) || ($user == null) || ($career == null)){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $student = new Student();
        $student ->setItsonId($itson_id);
        $student->setFirstName($firstName);
        $student->setLastName($lastName);
        $student->setUser($user);
        $student->setCareer($career);

        $result = $control->insertStudent( $student);

        return $response->withStatus( Utils::$CREATED )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
    //{"itson_id":"132456","first_name":"pepito","last_name":"lopez","user":"5","career":"1"}

});

$app->post('/update', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['id']))  || (!isset($body['itson_id'])) || (!isset($body['first_name'])) || (!isset($body['last_name'])) || (!isset($body['status'])) || (!isset($body['user'])) || (!isset($body['career'])) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new StudentControl();
        //Registrando
        $id = $body['id'];
        $itson_id = $body['itson_id'];
        $firstName = $body['first_name'];
        $lastName = $body['last_name'];
        $status = $body['status'];
        $user = $body['user'];
        $career = $body['career'];

        if( ($id == null) || ($itson_id == null) || ($firstName == null) || ($lastName == null) || ($status == null) || ($user == null) || ($career == null)){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $student = new Student();
        $student ->setId($id);
        $student ->setItsonId($itson_id);
        $student->setFirstName($firstName);
        $student->setLastName($lastName);
        $student->setStatus($status);
        $student->setUser($user);
        $student->setCareer($career);

        $result = $control->updateStudent( $student );

        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

    // { "id":"2", "itson_id":"56346", "first_name":"vianey", "last_name":"navarro", "user":"5", "status":"1", "career":"1"}
});

$app->post('/delete', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['id'])) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new StudentControl();
        //Registrando
        $id = $body['id'];

        if( $id == null ){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $result = $control->deleteStudent( $id );
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});

$app->post('/search', function (Request $request, Response $response) {

    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['search'])) || (!isset($body['role'])) || (!isset($body['search_by']))  )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new StudentControl();
        //Registrando
        $role = $body['role'];
        $search_by = $body['search_by'];
        $search = $body['search'];

        if( ($search_by == null) || ($search == null) || ($role == null) ){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $res = null;
        if($role === 'admin'){
            $res = $control->getStudent_Admin($search_by, $search);
        }else{
            $res = $control->search_Student($search_by, $search);
        }

        return $response->withStatus( Utils::$OK )->withJson( $res );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

    // { "role":"Admin","search_by":"name","search":"Edu"}
});
    $app->run();
