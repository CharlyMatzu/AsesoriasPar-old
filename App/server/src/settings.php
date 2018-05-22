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
    return new \Middelware\InputParamsMiddelware();
};

//-----------------------
//Controllers methods
//-----------------------

$container['UserController'] = function($c){
    return new \Controller\UserController();
};
$container['StudentController'] = function($c){
    return new \Controller\StudentController();
};
$container['CareerController'] = function($c){
    return new \Controller\CareerController();
};
$container['PlanController'] = function($c){
    return new \Controller\PlanController();
};
$container['SubjectController'] = function($c){
    return new \Controller\SubjectController();
};
$container['PeriodController'] = function($c){
    return new \Controller\PeriodController();
};
$container['ScheduleController'] = function($c){
    return new \Controller\ScheduleController();
};
$container['AdvisoryController'] = function($c){
    return new \Controller\StudentController();
};