<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Objects\User;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;
use Control\UserControl;
use Control\Auth;


//$app = new App(['settings' => ['displayErrorDetails' => true]]);
$app = new App();

//-----------GET METOD

//Obtener informacion del usuario actual
$app->get('/', function (Request $request, Response $response) {

    try{
        //Verificamos si esta autorizado
        $id = Auth::authorize( $request, Utils::$ROLE_BASIC );

        $control = new UserControl();
        $result = $control->getUser_ById($id);
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});


$app->get('/{id}', function (Request $request, Response $response, $params) {

    try{
        //Verificamos si esta autorizado
        Auth::authorize( $request, Utils::$ROLE_BASIC );

        //se obtiene parametro
        if( !isset($params['id']) ){
            if( empty($params['id']) )
                return $response->withStatus(Utils::$BAD_REQUEST)
                    ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $id = $params['id'];
        $control = new UserControl();
        $result = $control->getUser_ById($id);
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});


//Informacion de todos los usuarios
$app->get('/all', function (Request $request, Response $response) {

    try{
        //Verificamos si esta autorizado
        Auth::authorize( $request, Utils::$ROLE_MOD );

        $control = new UserControl();
        $result = $control->getUsers();
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

});



$app->get('/active', function (Request $request, Response $response) {
    try{
        Auth::authorize( $request, Utils::$ROLE_BASIC );

        $control = new UserControl();
        $result = $control->getActiveUsers();
        return $response->withJson( $result );
    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
});


//Regstro de usuario
$app->post('/', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['email'])) || (!isset($body['password']) || (!isset($body['role'])) ) )
       return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new UserControl();
        //Registrando
        $email = $body['email'];
        $password = $body['password'];
        $role = $body['role'];

        if( ($email == null) || ($password == null) || ($role == null) ){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);

        //Validando authorizacion
        Auth::authorize( $request, Utils::$ROLE_BASIC );

        $result = $control->insertUser( $user );

        return $response->withStatus( Utils::$CREATED )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

    // { "email":"nuevo@gmail.com","password":"555","role":"student"}

});

$app->put('/', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();
    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['id'])) || (!isset($body['email'])) || (!isset($body['password']) || (!isset($body['role'])) ) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        //Obteniendo datos
        $control = new UserControl();
        //Registrando
        $id = $body['id'];
        $email = $body['email'];
        $password = $body['password'];
        $role = $body['role'];

        if( ($id == null) || ($email == null) || ($password == null) || ($role == null) ){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        $user = new User();
        $user->setId($id);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);

        //Validando authorizacion
        Auth::authorize( $request, Utils::$ROLE_BASIC );

        $result = $control->updateUser( $user );

        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }

    // { "id":"20","email":"editado3@gmail.com","password":"555","role":"student"}
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
        //Obteniendo datos
        $control = new UserControl();
        //Registrando
        $id = $body['id'];

        if( $id == null ){
            return $response->withStatus(Utils::$BAD_REQUEST)
                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
        }

        //Validando authorizacion
        Auth::authorize( $request, Utils::$ROLE_BASIC );

        $result = $control->deleteUser( $id );
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
    //{ "id":"6" }
});

$app->post('/auth', function (Request $request, Response $response) {
    //Se obtiene el json y se transforma en array
    $body = $request->getParsedBody();

    //Mando incormacion incorrecta
    if( ($body == null) || (!isset($body['email'])) || (!isset($body['password'])) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    $email = $body['email'];
    $pass = $body['password'];

    if( empty($email) || empty($pass) )
        return $response->withStatus(Utils::$BAD_REQUEST)
            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );

    //TODO: validar que vengan los campos requeridos
    try{
        $con = new UserControl();
        $result = $con->signIn($email, $pass);
        return $response->withStatus( Utils::$OK )->withJson( $result );

    }catch (RequestException $ex){
        return $response->withStatus( $ex->getRequestStatusCode() )
            ->withJson( Utils::makeArrayResponse( $ex->getMessage() ) );
    }
    //{ "id":"6" }
});



//$app->post('/search', function (Request $request, Response $response) {
//    //Se obtiene el json y se transforma en array
//    $body = $request->getParsedBody();
//    //Mando incormacion incorrecta
//    if( ($body == null) || (!isset($body['search'])) || (!isset($body['search_by']))  )
//        return $response->withStatus(Utils::$BAD_REQUEST)
//            ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula") );
//
//    //TODO: validar que vengan los campos requeridos
//    try{
//        //Obteniendo datos
//        $control = new UserControl();
//        //Registrando
//        $search_by = $body['search_by'];
//        $search = $body['search'];
//
//        if( ($search_by == null) || ($search == null) ){
//            return $response->withStatus(Utils::$BAD_REQUEST)
//                ->withJson( Utils::makeArrayResponse("Informacion es incorrecta o nula", "400 Bad Request") );
//        }
//
//        $result = $control->searchUser( $search_by, $search );
//        return $response->withStatus( Utils::$OK )->withJson( $result );
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
