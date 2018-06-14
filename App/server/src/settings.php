<?php

$container = $app->getContainer();

//----------------
// CORS
//----------------
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");


//-----------------------
// MONOLOG
//-----------------------
//https://akrabat.com/logging-errors-in-slim-3/
//https://www.slimframework.com/docs/v3/tutorial/first-app.html

 $container['errorHandler'] = function($c) {
     /**
      * @param $request \Slim\Http\Request
      * @param $response \Slim\Http\Response
      * @param $ex RuntimeException
      * @return mixed
      */
    return function ($request, $response, $ex) use ($c){
        \App\AppLogger::makeErrorLog( "ErrorHandler",
            "File: ".$ex->getFile()."[".$ex->getLine()."] --- ".$ex->getMessage());

        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Algo salio mal, intentelo más tarde' );
    };
 };


//Override the default Not Found Handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('No se encontró la página solicitada');
    };
};

//Override the default Not Found Handler
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Content-Type', 'text/html')
            ->write('Métodos deben ser uno de: ' . implode(',', $methods));
    };
};

$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        \App\AppLogger::makeErrorLog("PHPErrorHandler","Ocurrio un error: $error");

        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Ocurrio un error, intentelo más tarde');
    };
};

//------------------------------------------- DEPENDENCY INJECTION (DI)

//-----------------------
//Middleware methods
//-----------------------
$container['InputMiddleware'] = function($c){
    return new App\Middleware\InputParamsMiddleware();
};

$container['AuthMiddleware'] = function($c){
    return new App\Middleware\AuthMiddleware();
};

//-----------------------
//Controllers methods
//-----------------------
$container['MailController'] = function($c){
    return new App\Controller\MailController();
};

$container['AuthController'] = function($c){
    return new App\Controller\AuthController();
};

$container['UserController'] = function($c){
    return new App\Controller\UserController();
};
$container['StudentController'] = function($c){
    return new App\Controller\StudentController();
};
$container['CareerController'] = function($c){
    return new App\Controller\CareerController();
};
$container['PlanController'] = function($c){
    return new App\Controller\PlanController();
};
$container['SubjectController'] = function($c){
    return new App\Controller\SubjectController();
};
$container['PeriodController'] = function($c){
    return new App\Controller\PeriodController();
};
$container['ScheduleController'] = function($c){
    return new App\Controller\ScheduleController();
};
$container['AdvisoryController'] = function($c){
    return new App\Controller\AdvisoryController();
};