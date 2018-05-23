<?php namespace Api;

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'src/autoload.php';


use App\Exceptions\InternalErrorException;
use App\Middelware\AuthMiddelware;
use Monolog\Logger;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;


$config = [
    'settings' => [
        'displayErrorDetails' => false,
    ],
];

//Instanciando APP
$app = new App($config);
//Contenedores de controlladores y midd
require_once 'src/settings.php';

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


//TODO: agregar un status por defacul directamente en Persistencia para evitar problemas en DB


$app->get('/', function(Request $request, Response $response, $params){
    //TODO: retorn API routes in JSON
    $response->write("Welcome to the API");
});

//--------------------------
//  USER ROUTES
//--------------------------
$app->get('/users', 'UserController:getUsers')
        ->add(AuthMiddelware::class);


$app->get('/users/status/{status}', 'UserController:getUsersByStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add(AuthMiddelware::class);


$app->get('/users/search/{email}', 'UserController:searchUsersByEmail')
    ->add('InputMiddelware:checkParam_Email')
    ->add(AuthMiddelware::class);

//TODO: obtener por rol

$app->get('/users/{id}', 'UserController:getUser_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->post('/users', 'UserController:createUser')
        ->add('InputMiddelware:checkData_User') //Es el registro de estudiante
        ->add('InputMiddelware:checkData_Role'); //Es el registro de estudiante

//TODO: ruta para confirmar usuario---> GET: user/confirm/{token}

$app->post('/users/student', 'UserController:createUserAndStudent')
        ->add('InputMiddelware:checkData_Student') //Es el registro de estudiante
        ->add('InputMiddelware:checkData_User'); //Es el registro de usuario (se ejecuta primero)


$app->post('/users/auth', 'UserController:auth')
        ->add('InputMiddelware:checkData_Auth'); //Es el inicio de sesion


$app->put('/users/{id}', 'UserController:updateUser')
        ->add('InputMiddelware:checkData_User')
        ->add('InputMiddelware:checkData_Role')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->patch('/users/{id}/status/{status}', 'UserController:changeStatusUser')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->delete('/users/{id}', 'UserController:deleteUser')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//--------------------------
//  CAREER ROUTES
//--------------------------
$app->get('/careers', 'CareerController:getCareers')
        ->add(AuthMiddelware::class);

$app->get('/careers/{id}', 'CareerController:getCareer_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->post('/careers', 'CareerController:createCareer')
        ->add('InputMiddelware:checkData_Career')
        ->add(AuthMiddelware::class);

$app->patch('/careers/{id}/status/{status}', 'CareerController:changeStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->put('/careers/{id}', 'CareerController:updateCareer')
        ->add('InputMiddelware:checkData_Career')
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
        ->add('InputMiddelware:checkData_Plan')
        ->add(AuthMiddelware::class);

$app->patch('/plans/{id}/status/{status}', 'PlanController:changeStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

$app->put('/plans/{id}', 'PlanController:updatePlan')
        ->add('InputMiddelware:checkData_Plan')
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
        ->add('InputMiddelware:checkData_Subject')
        ->add(AuthMiddelware::class);


$app->patch('/subjects/{id}/status/{status}', 'SubjectController:chanteStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);


$app->put('/subjects/{id}', 'SubjectController:updateSubject')
        ->add('InputMiddelware:checkData_Subject')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);


$app->delete('/subjects/{id}', 'SubjectController:deleteSubject')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);


//--------------------------
//  STUDENT ROUTES
//--------------------------
$app->get('/students', 'StudentController:getStudents')
    ->add(AuthMiddelware::class);


$app->get('/students/{id}', 'StudentController:getStudent_ById')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);

$app->get('/students/search/{student_data}', 'StudentController:searchStudents')
//    ->add('InputMiddelware:checkParam_Data')
    ->add(AuthMiddelware::class);


//Obtener correos tipo gmail para agregar y enviarles correos
//$app->get('/students/search/{student_data}', 'StudentController:searchStudents')
//    ->add('InputMiddelware:checkParam_Id')
//    ->add(AuthMiddelware::class);


$app->put('/students/{id}', 'StudentController:updateStudent')
    ->add('InputMiddelware:checkData_Student')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);


//--------------------------
//  PERIOD ROUTES
//--------------------------
$app->get('/periods', 'PeriodController:getPeriods')
    ->add(AuthMiddelware::class);

//TODO: Obtener asesorias de un periodo

$app->get('/periods/{id}', 'PeriodController:getPeriod_ById')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);

$app->post('/periods', 'PeriodController:createPeriod')
    ->add('InputMiddelware:checkData_Period')
    ->add(AuthMiddelware::class);

$app->put('/periods/{id}', 'PeriodController:updatePeriod')
    ->add('InputMiddelware:checkData_Period')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);


$app->patch('/periods/{id}/status/{status}', 'PeriodController:changeStatus')
    ->add('InputMiddelware:checkParam_Status')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);


$app->delete('/periods/{id}', 'PeriodController:deletePeriod')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);


//--------------------------
//  SCHEDULE, HOURS AND DAYS ROUTES
//--------------------------

$app->get('/schedule/source', 'ScheduleController:getHoursAndDays')
    ->add(AuthMiddelware::class);


$app->get('/schedule/{id}', 'ScheduleController:getSchedule_ById')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);


//-------------------ESTUDIANTE

$app->get('/students/{id}/schedule', 'StudentController:getCurrentStudentSchedule_ById')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);

//TODO: obtener horario especifico de alumno

$app->post('/students/{id}/schedule', 'StudentController:createSchedule')
        ->add('InputMiddelware:checkData_schedule_hours')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


$app->post('/students/{id}/schedule/{schedule}/subjects', 'StudentController:addScheduleSubjects')
        ->add('InputMiddelware:checkData_schedule_subjects')
        ->add('InputMiddelware:checkParam_Schedule')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


$app->put('/students/{id}/schedule/{schedule}/hours', 'StudentController:updateScheduleHours')
        ->add('InputMiddelware:checkData_schedule_hours')
        ->add('InputMiddelware:checkParam_Schedule')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


$app->put('/students/{id}/schedule/{schedule}/subjects', 'StudentController:updateScheduleSubjects')
        ->add('InputMiddelware:checkData_schedule_subjects')
        ->add('InputMiddelware:checkParam_Schedule')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


//TODO: validacion de materias de horario
//TODO: agregar: deshabilitar/habilitar horario
//TODO: agregar: eliminar
//TODO: agregar: actualizar horas/materias de horario


//--------------------------
//  ADVISORY ROUTES
//--------------------------
//TODO: debe obtenerse solo materias validadas por admin

$app->get('/advisory', 'AdvisoryController:getAdvisorys');
$app->get('/advisory/{id}', 'AdvisoryController:getAdvisory_ById');
$app->post('/advisory', 'AdvisoryController:createAdvisory');
$app->put('/advisory/{id}', 'AdvisoryController:updateAdvisory');
$app->delete('/advisory/{id}', 'AdvisoryController:deleteAdvisory');




try{
    $app->run();
} catch (MethodNotAllowedException $e) {
    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
} catch (NotFoundException $e) {
    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
} catch (\Exception $e) {
    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
}