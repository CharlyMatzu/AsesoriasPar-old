<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';

require_once 'vendor/autoload.php';


use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;


$app = new App();
//Contenedores de controlladores y midd
require_once 'includes/settings.php';


$app->get('/', function(Request $request, Response $response, $params){
    $response->write("Hello world");
});

//--------------------------
//  USER ROUTES
//--------------------------
//$app->get('/users', 'UserController:getUsers');

//--------------------------
//  STUDENT ROUTES
//--------------------------

//--------------------------
//  CAREER ROUTES
//--------------------------

//--------------------------
//  PLAN ROUTES
//--------------------------

//--------------------------
//  SUBJECT ROUTES
//--------------------------


//--------------------------
//  PERIOD ROUTES
//--------------------------


//--------------------------
//  SCHEDULE ROUTES
//--------------------------


//--------------------------
//  ADVISORY ROUTES
//--------------------------




//TODO: Handle exceptions
try{
    $app->run();
} catch (MethodNotAllowedException $e) {
    //http_response_code(Utils::$INTERNAL_SERVER_ERROR);
    exit("Metodo no permitido");
} catch (NotFoundException $e) {
    exit("No encontrado");
} catch (\Exception $e) {
    exit($e->getMessage());
}