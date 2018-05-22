<?php

$container = $app->getContainer();

//----------------
// CORS
//----------------
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");

//-----------------------
//Middelware methods
//-----------------------
$container['InputMiddelware'] = function($c){
    return new App\Middelware\InputParamsMiddelware();
};

//-----------------------
//Controllers methods
//-----------------------

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
    return new App\Controller\StudentController();
};