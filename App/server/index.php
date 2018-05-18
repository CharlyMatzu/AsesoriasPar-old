<?php namespace Api;

require_once 'config.php';
require_once 'includes/autoload.php';
require_once 'vendor/autoload.php';


use Middelware\AuthMiddelware;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;


$config = [
    'settings' => [
        'displayErrorDetails' => true,

//        'logger' => [
//            'name' => 'slim-app',
//            'level' => Monolog\Logger::DEBUG, //Se requiere Monolog
//            'path' => __DIR__ . '/../logs/app.log',
//        ],
    ],
];

//Instanciando APP
$app = new App($config);
//Contenedores de controlladores y midd
require_once 'includes/settings.php';

//--------- NOTA:
// --Los middelware se ejecutan antes y despues que los controllers
// --Se usa el getBody para escribir en el response sin enviarlo
// --Los middelware y controllers siempre deben retornar el response
// --Los Middelware reciben un callable referente al siguiente middelware o controller el cual deben llamar ($next)
// el cual retorna un response para ser manejado desde el midd
// --Para pasar parametros entre middelwares,se usa:
//      Para enviar: $request = $request->withAttribute('foo', 'bar');
//      Para obtener: $foo = $request->getAttribute('foo');
//--------NOTA:
// --se puede agregar un middelware global aregandolo directamente a $app y no a un verbo GET, POST, etc.
// --El orden de ejecucion de lod MID es LIFO (pila)
// --Se debe obtener los parametros directamente del $request cuando este es un Middelware,
//  en un controller se recibe un "array" como parametro




$app->get('/', function(Request $request, Response $response, $params){
    //TODO: retorn API routes in JSON
    $response->write("Welcome to the API");
});

//--------------------------
//  USER ROUTES
//--------------------------
$app->get('/users', 'UserController:getUsers')
        ->add(AuthMiddelware::class);

$app->get('/users/{id}', 'UserController:getUser_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->post('/users/signup', 'UserController:createUser')
        ->add('InputMiddelware:checkData_user'); //Es el registro de estudiante

$app->post('/users/student/signup', 'UserController:createUserAndStudent')
    ->add('InputMiddelware:checkData_student') //Es el registro de estudiante
    ->add('InputMiddelware:checkData_user'); //Es el registro de usuario (se ejecuta primero)

$app->post('/users/auth', 'UserController:auth')
        ->add('InputMiddelware:checkData_Auth'); //Es el inicio de sesion

$app->put('/users/{id}', 'UserController:updateUser')
        ->add('InputMiddelware:checkData_user')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


$app->delete('/users/{id}', 'UserController:deleteUser')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//--------------------------
//  STUDENT ROUTES
//--------------------------
$app->get('/students', 'StudentController:getStudents')
        ->add(AuthMiddelware::class);

$app->get('/students/{id}', 'StudentController:getStudent_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//$app->put('/students', 'StudentController:updateStudent');
//$app->delete('/students', 'StudentController:deleteStudent');

//--------------------------
//  CAREER ROUTES
//--------------------------
$app->get('/careers', 'CareerController:getCareers')
        ->add(AuthMiddelware::class);

$app->get('/careers/{id}', 'CareerController:getCareer_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->post('/careers', 'CareerController:createCareer')
        ->add('InputMiddelware:checkData_career')
        ->add(AuthMiddelware::class);

$app->put('/careers/{id}', 'CareerController:updateCareer')
        ->add('InputMiddelware:checkData_career')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


$app->delete('/careers/{id}', 'CareerController:deleteCareer')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//--------------------------
//  PLAN ROUTES
//--------------------------
$app->get('/plans', 'PlanController:getPlans')
        ->add(AuthMiddelware::class);

$app->get('/plans/{id}', 'PlanController:getPlan_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->post('/plans', 'PlanController:createPlan')
        ->add('InputMiddelware:checkData_plan')
        ->add(AuthMiddelware::class);

$app->put('/plans/{id}', 'PlanController:updatePlan')
        ->add('InputMiddelware:checkData_plan')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->delete('/plans/{id}', 'PlanController:deletePlan')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//--------------------------
//  SUBJECT ROUTES
//--------------------------
$app->get('/subjects', 'SubjectController:getSubjects')
        ->add(AuthMiddelware::class);

//TODO: agregar ruta directo de career --> /career/{id}/subject/{id}
$app->get('/subjects/{id}', 'SubjectController:getSubject_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->post('/subjects', 'SubjectController:createSubject')
        ->add('InputMiddelware:checkData_subject')
        ->add(AuthMiddelware::class);

$app->put('/subjects/{id}', 'SubjectController:updateSubject')
        ->add('InputMiddelware:checkData_subject')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

$app->delete('/subjects/{id}', 'SubjectController:deleteSubject')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

//--------------------------
//  PERIOD ROUTES
//--------------------------
$app->get('/periods', 'PeriodController:getPeriods')
        ->add(AuthMiddelware::class);

$app->get('/periods/{id}', 'PeriodController:getPeriod_ById')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

$app->post('/periods', 'PeriodController:createPeriod')
        ->add('InputMiddelware:checkData_period')
        ->add(AuthMiddelware::class);

$app->put('/periods/{id}', 'PeriodController:updatePeriod')
        ->add('InputMiddelware:checkData_period')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

$app->delete('/periods/{id}', 'PeriodController:deletePeriod')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

//--------------------------
//  HOURS AND DAYS ROUTES
//--------------------------

//--------------------------
//  SCHEDULE ROUTES
//--------------------------
//TODO: agregar a ruta de estudiante para registrar y obtener de estudiante --> /student/{id}/schedule

$app->get('/schedule', 'ScheduleController:getHoursAndDays')
    ->add(AuthMiddelware::class);

$app->get('/schedule/{id}', 'ScheduleController:getSchedule_ById')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);




//--------------------------
//  ADVISORY ROUTES
//--------------------------
$app->get('/advisory', 'AdvisoryController:getAdvisorys');
$app->get('/advisory/{id}', 'AdvisoryController:getAdvisory_ById');
$app->post('/advisory', 'AdvisoryController:createAdvisory');
$app->put('/advisory/{id}', 'AdvisoryController:updateAdvisory');
$app->delete('/advisory/{id}', 'AdvisoryController:deleteAdvisory');



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