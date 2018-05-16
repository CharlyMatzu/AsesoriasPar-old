<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Control\Auth;
use Exceptions\RequestException;
use Model\Student;
use Control\StudentControl;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use Utils;
use \Slim\App;

use Control\UserService;

$app = new App;


$app->get('/', function (Request $request, Response $response) {
    try{
        //Autorizar usuario
        $id = Auth::authorize( $request, Utils::$ROLE_BASIC );

        $control = new StudentControl();
        $result = $control->getStudent_ById($id);
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


$app->get('/{id}', function (Request $request, Response $response, $params) {
    //$id = $params['id'];
    try{
        //Autorizar usuario
        $id = Auth::authorize( $request, Utils::$ROLE_BASIC );

        $control = new StudentControl();
        $result = $control->getStudent_ById($id);
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


$app->get('/all', function (Request $request, Response $response) {
    try{
        //Autorizar usuario
        Auth::authorize( $request, Utils::$ROLE_MOD );

        $control = new StudentControl();
        $result = $control->getStudents();
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


$app->post('/', function (Request $request, Response $response) {
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
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
    //{"itson_id":"132456","first_name":"pepito","last_name":"lopez","user":"5","career":"1"}

});

$app->put('/', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['id']))  || (!isset($body['itson_id'])) || (!isset($body['first_name'])) || (!isset($body['last_name'])) || (!isset($body['status'])) || (!isset($body['user'])) || (!isset($body['career'])) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //TODO: verificar que sea el mismo usuario quien se modifica a si mismo
        //Autorizar usuario
        Auth::authorize( $request, Utils::$ROLE_BASIC );

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
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

    // { "id":"2", "itson_id":"56346", "first_name":"vianey", "last_name":"navarro", "user":"5", "status":"1", "career":"1"}
});


$app->delete('/', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['id'])) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //TODO: verificar que sea el mismo usuario quien se modifica a si mismo
        //Autorizar usuario
        Auth::authorize( $request, Utils::$ROLE_BASIC );


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
        return $response->withStatus( $ex->getStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});

//$app->post('/search', function (Request $request, Response $response) {
//
//    $body = $request->getParsedBody();
//    //Mando incormacion incorrecta
//    if( ($body == null) || (!isset($body['search'])) || (!isset($body['role'])) || (!isset($body['search_by']))  )
//        return $response->withStatus(Utils::$BAD_REQUEST)
//            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );
//
//    //TODO: validar que vengan los campos requeridos
//    try{
//        //Obteniendo datos
//        $Service = new StudentControl();
//        //Registrando
//        $role = $body['role'];
//        $search_by = $body['search_by'];
//        $search = $body['search'];
//
//        if( ($search_by == null) || ($search == null) || ($role == null) ){
//            return $response->withStatus(Utils::$BAD_REQUEST)
//                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
//        }
//
//        $res = null;
//        if($role === 'admin'){
//            $res = $Service->getStudent_Admin($search_by, $search);
//        }else{
//            $res = $Service->search_Student($search_by, $search);
//        }
//
//        return $response->withStatus( Utils::$OK )->withJson( $res );
//
//    }catch (RequestException $ex){
//        return $response->withStatus( $ex->getRequestStatusCode() )
//            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
//    }
//
//    // { "role":"Admin","search_by":"name","search":"Edu"}
//});


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
