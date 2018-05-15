<?php

$container = $app->getContainer();

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