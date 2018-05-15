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
$app->get('/users', 'UserController:getUsers');
$app->get('/users/{id}', 'UserController:getUser_ById');
$app->post('/users', 'UserController:createUser');
$app->put('/users', 'UserController:updateUser');
$app->delete('/users', 'UserController:deleteUser');

//--------------------------
//  STUDENT ROUTES
//--------------------------
$app->get('/students', 'StudentController:getStudents');
$app->get('/students/{id}', 'StudentController:getStudent_ById');
$app->post('/students', 'StudentController:createStudent');
$app->put('/students', 'StudentController:updateStudent');
$app->delete('/students', 'StudentController:deleteStudent');

//--------------------------
//  CAREER ROUTES
//--------------------------
$app->get('/careers', 'CareerController:getCareers');
$app->get('/careers/{id}', 'CareerController:getCareer_ById');
$app->post('/careers', 'CareerController:createCareer');
$app->put('/careers', 'CareerController:updateCareer');
$app->delete('/careers', 'CareerController:deleteCareer');

//--------------------------
//  PLAN ROUTES
//--------------------------
$app->get('/plan', 'PlanController:getPlans');
$app->get('/plan/{id}', 'PlanController:getPlan_ById');
$app->post('/plan', 'PlanController:createPlan');
$app->put('/plan', 'PlanController:updatePlan');
$app->delete('/plan', 'PlanController:deletePlan');

//--------------------------
//  SUBJECT ROUTES
//--------------------------
$app->get('/subject', 'SubjectController:getSubjects');
$app->get('/subject/{id}', 'SubjectController:getSubject_ById');
$app->post('/subject', 'SubjectController:createSubject');
$app->put('/subject', 'SubjectController:updateSubject');
$app->delete('/subject', 'SubjectController:deleteSubject');

//--------------------------
//  PERIOD ROUTES
//--------------------------
$app->get('/period', 'PeriodController:getPeriods');
$app->get('/period/{id}', 'PeriodController:getPeriod_ById');
$app->post('/period', 'PeriodController:createPeriod');
$app->put('/period', 'PeriodController:updatePeriod');
$app->delete('/period', 'PeriodController:deletePeriod');

//--------------------------
//  SCHEDULE ROUTES
//--------------------------
$app->get('/schedule', 'ScheduleController:getSchedules');
$app->get('/schedule/{id}', 'ScheduleController:getSchedule_ById');
$app->post('/schedule', 'ScheduleController:createSchedule');
$app->put('/schedule', 'ScheduleController:updateSchedule');
$app->delete('/schedule', 'ScheduleController:deleteSchedule');

//--------------------------
//  ADVISORY ROUTES
//--------------------------
$app->get('/advisory', 'AdvisoryController:getAdvisorys');
$app->get('/advisory/{id}', 'AdvisoryController:getAdvisory_ById');
$app->post('/advisory', 'AdvisoryController:createAdvisory');
$app->put('/advisory', 'AdvisoryController:updateAdvisory');
$app->delete('/advisory', 'AdvisoryController:deleteAdvisory');



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