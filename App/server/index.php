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

//TODO: quitar cuando este en produccion
$config = [
    'settings' => [
        'displayErrorDetails' => true,
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

//Obtiene todos los usuarios
$app->get('/users', 'UserController:getUsers')
        ->add(AuthMiddelware::class);


//Obtiene todos los usuarios con rol: Mod/Admin
$app->get('/users/staff', 'UserController:getStaffUsers')
    ->add(AuthMiddelware::class);

//TODO: hacer mas descriptiva
//Obtiene usuarios con un status especifico
$app->get('/users/status/{status}', 'UserController:getUsersByStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add(AuthMiddelware::class);

//busca usuarios por correo (coincidencias)
$app->get('/users/search/{email}', 'UserController:searchUsersByEmail')
    ->add('InputMiddelware:checkParam_Email')
    ->add(AuthMiddelware::class);

//busca usuarios por correo (coincidencias)
$app->get('/users/search/{email}/staff', 'UserController:searchStaffUsersByEmail')
    ->add('InputMiddelware:checkParam_Email')
    ->add(AuthMiddelware::class);

//TODO: obtener por rol

//Obtiene usuario por ID
$app->get('/users/{id}', 'UserController:getUser_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//Crear un usuario simple
$app->post('/users', 'UserController:createUser')
        ->add('InputMiddelware:checkData_User') //Es el registro de estudiante
        ->add('InputMiddelware:checkData_Role'); //Es el registro de estudiante

//TODO: ruta para confirmar usuario---> GET: user/confirm/{token}

//Crear un usuario y un estudiante a la vez
$app->post('/users/student', 'UserController:studentSignup')
        ->add('InputMiddelware:checkData_Student') //Es el registro de estudiante
        ->add('InputMiddelware:checkData_User'); //Es el registro de usuario (se ejecuta primero)

//Permite autenticarse (signin)
$app->post('/users/auth', 'UserController:auth')
        ->add('InputMiddelware:checkData_Auth'); //Es el inicio de sesion

//Actualiza datos de usuario
$app->put('/users/{id}', 'UserController:updateUser')
        ->add('InputMiddelware:checkData_User')
        ->add('InputMiddelware:checkData_Role')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//Cambia estado de usuario
$app->patch('/users/{id}/status/{status}', 'UserController:changeStatusUser')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//Elimina a un usuario
$app->delete('/users/{id}', 'UserController:deleteUser')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//--------------------------
//  CAREER ROUTES
//--------------------------

//Obtiene carreras
$app->get('/careers', 'CareerController:getCareers')
        ->add(AuthMiddelware::class);

//Obtiene carrera por Id
$app->get('/careers/{id}', 'CareerController:getCareer_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//TODO: obtener materias por carrera

//Crear carrera
$app->post('/careers', 'CareerController:createCareer')
        ->add('InputMiddelware:checkData_Career')
        ->add(AuthMiddelware::class);

//Cambia estado de carrera
$app->patch('/careers/{id}/status/{status}', 'CareerController:changeStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//Actualiza carrera
$app->put('/careers/{id}', 'CareerController:updateCareer')
        ->add('InputMiddelware:checkData_Career')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//Elimina carrera
$app->delete('/careers/{id}', 'CareerController:deleteCareer')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


//--------------------------
//  PLAN ROUTES
//--------------------------

//Obtiene planes
$app->get('/plans', 'PlanController:getPlans')
        ->add(AuthMiddelware::class);

//Obtiene plan por id
$app->get('/plans/{id}', 'PlanController:getPlan_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//TODO: obtener materias por plan

//Crea un plan
$app->post('/plans', 'PlanController:createPlan')
        ->add('InputMiddelware:checkData_Plan')
        ->add(AuthMiddelware::class);

//TODO: hacer mas descriptivo
//cambia status de un plan
$app->patch('/plans/{id}/status/{status}', 'PlanController:changeStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


//Actualiza plan
$app->put('/plans/{id}', 'PlanController:updatePlan')
        ->add('InputMiddelware:checkData_Plan')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//elimina plan
$app->delete('/plans/{id}', 'PlanController:deletePlan')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//--------------------------
//  SUBJECT ROUTES
//--------------------------

//Obtiene materias
$app->get('/subjects', 'SubjectController:getSubjects')
        ->add(AuthMiddelware::class);

//TODO: agregar ruta directo de career --> /career/{id}/subject/{id}

//Obtiene materia por id
$app->get('/subjects/{id}', 'SubjectController:getSubject_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//crear materia
$app->post('/subjects', 'SubjectController:createSubject')
        ->add('InputMiddelware:checkData_Subject')
        ->add(AuthMiddelware::class);


//cambia estado de materia
$app->patch('/subjects/{id}/status/{status}', 'SubjectController:changeStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

//Actualiza materia
$app->put('/subjects/{id}', 'SubjectController:updateSubject')
        ->add('InputMiddelware:checkData_Subject')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

//Elimina materia
$app->delete('/subjects/{id}', 'SubjectController:deleteSubject')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);


//--------------------------
//  STUDENT ROUTES
//--------------------------

//Obtiene estudiantes
$app->get('/students', 'StudentController:getStudents')
    ->add(AuthMiddelware::class);

//obtiene estudiante por id
$app->get('/students/{id}', 'StudentController:getStudent_ById')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);

//Busca estudiante por coincidencias (todos los campos de string: nombre, apellido, correo, telefono)
$app->get('/students/search/{search_student}', 'StudentController:searchStudents')
    ->add('InputMiddelware:checkParam_search_student')
    ->add(AuthMiddelware::class);

//TODO: Obtener correos tipo gmail para agregar y enviarles correos

//Actualiza datos de usuario
//TODO: update avatar, facebook, etc.
$app->put('/students/{id}', 'StudentController:updateStudent')
    ->add('InputMiddelware:checkData_Student')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);


//--------------------------
//  PERIOD ROUTES
//--------------------------

//Obtiene periodos
$app->get('/periods', 'PeriodController:getPeriods')
    ->add(AuthMiddelware::class);

//TODO: Obtener asesorias de un periodo

//Obtiene periodo por id
$app->get('/periods/{id}', 'PeriodController:getPeriod_ById')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);

//Crea periodo
$app->post('/periods', 'PeriodController:createPeriod')
    ->add('InputMiddelware:checkData_Period')
    ->add(AuthMiddelware::class);

//actualiza periodo
$app->put('/periods/{id}', 'PeriodController:updatePeriod')
    ->add('InputMiddelware:checkData_Period')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);

//cambia status de periodo
$app->patch('/periods/{id}/status/{status}', 'PeriodController:changeStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

//elimina periodo
$app->delete('/periods/{id}', 'PeriodController:deletePeriod')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);


//--------------------------
//  SCHEDULE, HOURS AND DAYS ROUTES
//--------------------------

//Obtiene horas y dias disponibles para utilizar
$app->get('/schedule/source', 'ScheduleController:getHoursAndDays')
        ->add(AuthMiddelware::class);


//obtiene horario por id
$app->get('/schedule/{id}', 'ScheduleController:getSchedule_ById')
        ->add('InputMiddelware:checkParam_id')
        ->add(AuthMiddelware::class);

//Obtiene todos los asesores activos del periodo actual
//$app->get('/schedule/advisers', 'ScheduleController:getCurrentAdvisers')
//    ->add('InputMiddelware:checkParam_id')
//    ->add(AuthMiddelware::class);


//obtiene asesores disponibles (activos) por materia
$app->get('/subject/{id}/advisers', 'SubjectController:getCurrentAdvisers_BySubject')
    ->add('InputMiddelware:checkParam_id')
    ->add(AuthMiddelware::class);

//TODO: obtener asesores disponibles de dicha por materia
//TODO: obtener horario de Asesor y comparar con Solicitante


//Cambia estado de horario
$app->patch('/schedule/{id}/status/{status}', 'ScheduleController:changeScheduleStatus')
        ->add('InputMiddelware:checkParam_Status')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


//-------------------ESTUDIANTE

//Obtiene horario actual de estudiante
$app->get('/students/{id}/schedule', 'StudentController:getCurrentSchedule_ByStudentId')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);

//crea horario (horas)
$app->post('/students/{id}/schedule', 'StudentController:createSchedule')
        ->add('InputMiddelware:checkData_schedule_hours')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//agrega materias a horario
$app->post('/schedule/{id}/subjects', 'ScheduleController:addScheduleSubjects')
        ->add('InputMiddelware:checkData_schedule_subjects')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


//TODO: obtener materias de horario
//TODO: deshabilitar para ser asesor

//actualiza horas de horario
$app->put('/schedule/{id}/hours', 'ScheduleController:updateScheduleHours')
        ->add('InputMiddelware:checkData_schedule_hours')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//actualiza materias de horario
$app->put('/schedule/{id}/subjects', 'ScheduleController:updateScheduleSubjects')
        ->add('InputMiddelware:checkData_schedule_subjects')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);


//TODO: validacion de materias de horario (admin)
//TODO: agregar: deshabilitar/habilitar horario
//TODO: agregar: eliminar (admin)


//--------------------------
//  ADVISORY ROUTES
//--------------------------
//TODO: debe obtenerse solo materias validadas por admin (status = 2)
//TODO: no debe poder solictarse la misma materia mientras una asesoria este activa con la misma
//TODO: Al final del periodo, debe finalizarse todas las asesorias activas


//Obtiene asesorias actuales
$app->get('/advisories', 'AdvisoryController:getCurrentAdvisories')
        ->add(AuthMiddelware::class);


//Obtiene asesorias de estudiante
$app->get('/student/{id}/advisories', 'StudentController:getCurrentAdvisories_ByStudentId')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);

//Obtiene horas de asesoria asignada
$app->get('/advisories/{id}/hours', 'AdvisoryController:getAdvisoryHours_ById')
    ->add('InputMiddelware:checkParam_Id')
    ->add(AuthMiddelware::class);


//--------------STUDENT
//Obtiene asesoria por id
$app->get('/advisories/{id}', 'AdvisoryController:getAdvisory_ById')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//crear asesoria por estudiante
$app->post('/students/{id}/advisories', 'AdvisoryController:createStudentAdvisory')
        ->add('InputMiddelware:checkData_advisory')
        ->add('InputMiddelware:checkParam_Id')
        ->add(AuthMiddelware::class);

//asigna asesoria
//$app->post('/advisories/{id}/assign', 'AdvisoryController:assignAdviserAndHours')
//        ->add('InputMiddelware:checkParam_Id')
//        ->add(AuthMiddelware::class);

//$app->put('/advisories/{id}/hours', 'AdvisoryController:updateAdvisoryHours');
//
//$app->patch('/advisories/{id}/status/{status}', 'AdvisoryController:changeStatus');
//
//$app->delete('/advisories/{id}', 'AdvisoryController:deleteAdvisory');




try{
    $app->run();
} catch (MethodNotAllowedException $e) {
    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
} catch (NotFoundException $e) {
    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
} catch (\Exception $e) {
    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
}