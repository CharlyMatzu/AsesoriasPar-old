<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';

use Exceptions\RequestException;
use Objects\User;
use PHPMailer\PHPMailer\Exception;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;
use Utils;
use Control\UserControl;
use Control\Auth;


$app = new App();

$app->get( '/', function(Request $request, Response $response){
    $response->write("HELLO WORLD");
});

$app->get( '/test', function(Request $request, Response $response){
    $response->write("HELLO test");
})->add(function(Request $request, Response $response){
    $response->write("Soy el mid");
});



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