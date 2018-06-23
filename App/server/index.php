<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'src/autoload.php';


use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\App;


//TODO: quitar cuando este en producción
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
// --Los Middleware se ejecutan antes y después que los controllers
// --Se usa el getBody para escribir en el response sin enviarlo
// --Los Middleware y controllers siempre deben retornar el response
// --Los Middleware reciben un callable referente al siguiente Middleware o controller el cual deben llamar ($next)
// el cual retorna un response para ser manejado desde el midd
// --Para pasar Parámetros entre Middlewares,se usa:
//      Para enviar: $request = $request->withAttribute('foo', 'bar');
//      Para obtener: $foo = $request->getAttribute('foo');
//--------NOTA:
// --se puede agregar un Middleware global aregandolo directamente a $app y no a un verbo GET, POST, etc.
// --El orden de ejecucion de lod MID es LIFO (pila)
// --Se debe obtener los Parámetros directamente del $request cuando este es un Middleware,
//  en un controller se recibe un "array" como parámetros


//TODO: agregar un status por default directamente en Persistencia para evitar problemas en DB


$app->get('/', function(Request $request, Response $response, $params){
    //TODO: mostrar api
    $response->write("Welcome to the API");
});



//--------------------------
//  GENERAL routes
//--------------------------

$app->post('/mail/send', 'MailController:sendMail')
        ->add('InputMiddleware:checkData_Mail')
        ->add('AuthMiddleware:requireStaff');


//Permite autenticarse (signin)
$app->post('/auth/signin', 'AuthController:signin')
        ->add('InputMiddleware:checkData_Password')
        ->add('InputMiddleware:checkData_Email');


//Crear un usuario y un estudiante a la vez
$app->post('/auth/signup', 'AuthController:signup')
        ->add('InputMiddleware:checkData_Student') //Es el registro de estudiante
        ->add('InputMiddleware:checkData_Password')
        ->add('InputMiddleware:checkData_Email');

//TODO: ruta para actualizar password

//Para verificar usuario y activar
$app->get('/auth/confirm/{token}', 'AuthController:confirm');

//Para reenviar correo de confirmación
//$app->get('/auth/confirm/send', 'AuthController:sendConfirmEmail');


//--------------------------
//  USER ROUTES
//--------------------------


//Obtiene todos los usuarios
$app->get('/users', 'UserController:getUsers')
        ->add('AuthMiddleware:requireStaff');


//Crear un usuario simple
$app->post('/users', 'UserController:createUser')
//        ->add('AuthMiddleware:isAuthorized')
        ->add('InputMiddleware:checkData_Email')
        ->add('InputMiddleware:checkData_Password')
        ->add('InputMiddleware:checkData_Role') //Rol para que sea solo staff
        ->add('AuthMiddleware:requireAdmin');



//TODO: VALIDAR AUTH
//TODO: cambiar por solo cambio de password y de email separados
//Actualiza datos de usuario
$app->put('/users/{id}/email', 'UserController:updateUserEmail')
        ->add('InputMiddleware:checkData_Email')
        ->add('InputMiddleware:checkParam_Id')
        ->add('AuthMiddleware:requireBasic');


//TODO: debe recibir el password viejo y el nuevo
$app->put('/users/{id}/password', 'UserController:updateUserPassword')
        ->add('InputMiddleware:checkData_Password')
        ->add('InputMiddleware:checkParam_Id');
//        ->add('AuthMiddleware:requireBasic');


$app->put('/users/{id}/role', 'UserController:updateUserRole')
        ->add('InputMiddleware:checkData_Role')
        ->add('InputMiddleware:checkParam_Id');
//        ->add('AuthMiddleware:requireBasic');



//Cambia estado de usuario
$app->patch('/users/{id}/status/{status}', 'UserController:changeStatusUser')
        ->add('InputMiddleware:checkParam_Status')
        ->add('InputMiddleware:checkParam_Id')
        ->add('AuthMiddleware:requireAdmin');


//Elimina a un usuario
$app->delete('/users/{id}', 'UserController:deleteUser')
        ->add('InputMiddleware:checkParam_Id')
        ->add('AuthMiddleware:requireAdmin');



//Obtiene todos los usuarios con rol: Mod/Admin
$app->get('/users/staff', 'UserController:getStaffUsers')
        ->add('AuthMiddleware:requireStaff');


//busca usuarios por correo (coincidencias)
$app->get('/users/search/{search}', 'UserController:searchUsersByEmail')
        ->add('InputMiddleware:checkParam_Search')
        ->add('AuthMiddleware:requireStaff');


//busca usuarios por correo (coincidencias)
$app->get('/users/search/{email}/staff', 'UserController:searchStaffUsersByEmail')
        ->add('InputMiddleware:checkParam_Email')
        ->add('AuthMiddleware:requireStaff');



//Obtiene usuario por ID
$app->get('/users/{id}', 'UserController:getUser_ById')
        ->add('InputMiddleware:checkParam_Id');
//        ->add('AuthMiddleware:requireBasic');


//Obtener estudiante por id de usuario
$app->get('/users/{id}/student', 'UserController:getStudent_ByUserId')
        ->add('InputMiddleware:checkParam_Id')
        ->add('AuthMiddleware:requireBasic');



//Crear un usuario y un estudiante a la vez (el mismo de signup)
//$app->post('/users/student', 'UserController:createUserAndStudent')
//    ->add('InputMiddleware:checkData_Student') //Es el registro de estudiante
//    ->add('InputMiddleware:checkData_User')//Es el registro de usuario (se ejecuta primero)



//--------------------------
//  CAREER ROUTES
//--------------------------

//TODO: VALIDAR AUTH
//Obtiene carreras
$app->get('/careers', 'CareerController:getCareers');

//TODO: VALIDAR AUTH
//Obtiene carrera por Id
$app->get('/careers/{id}', 'CareerController:getCareer_ById')
        ->add('InputMiddleware:checkParam_Id');

//TODO: obtener materias por carrera

//TODO: VALIDAR AUTH
//Crear carrera
$app->post('/careers', 'CareerController:createCareer')
        ->add('InputMiddleware:checkData_Career');


//TODO: VALIDAR AUTH
//TODO: SEPARAR MÉTODOS: habilitar y deshabilitar
//Cambia estado de carrera
$app->patch('/careers/{id}/status/{status}', 'CareerController:changeStatus')
        ->add('InputMiddleware:checkParam_Status')
        ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
//Actualiza carrera
$app->put('/careers/{id}', 'CareerController:updateCareer')
        ->add('InputMiddleware:checkData_Career')
        ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//Elimina carrera
$app->delete('/careers/{id}', 'CareerController:deleteCareer')
        ->add('InputMiddleware:checkParam_Id');


//--------------------------
//  PLAN ROUTES
//--------------------------

//TODO: VALIDAR AUTH
//Obtiene planes
$app->get('/plans', 'PlanController:getPlans');

//TODO: VALIDAR AUTH
//Obtiene plan por id
$app->get('/plans/{id}', 'PlanController:getPlan_ById')
        ->add('InputMiddleware:checkParam_Id');

//TODO: obtener materias por plan

//TODO: VALIDAR AUTH
//Crea un plan
$app->post('/plans', 'PlanController:createPlan')
        ->add('InputMiddleware:checkData_Plan');

//TODO: VALIDAR AUTH
//TODO: hacer mas descriptivo
//TODO: SEPARAR MÉTODOS: habilitar y deshabilitar
//cambia status de un plan
$app->patch('/plans/{id}/status/{status}', 'PlanController:changeStatus')
        ->add('InputMiddleware:checkParam_Status')
        ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
//Actualiza plan
$app->put('/plans/{id}', 'PlanController:updatePlan')
        ->add('InputMiddleware:checkData_Plan')
        ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//elimina plan
$app->delete('/plans/{id}', 'PlanController:deletePlan')
        ->add('InputMiddleware:checkParam_Id');

//--------------------------
//  SUBJECT ROUTES
//--------------------------

//TODO: VALIDAR AUTH
//Obtiene materias
$app->get('/subjects', 'SubjectController:getSubjects');

//TODO: VALIDAR AUTH
$app->get('/subjects/enabled', 'SubjectController:getEnabledSubjects');

//TODO: VALIDAR AUTH
//Obtiene materias
$app->get('/subjects/carrera/{career}/semestre/{semester}/plan/{plan}', 'SubjectController:getSubject_Search');

//TODO: agregar ruta directo de career --> /career/{id}/subject/{id}

//TODO: VALIDAR AUTH
//Obtiene materia por id
$app->get('/subjects/{id}', 'SubjectController:getSubject_ById')
        ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//Obtiene materias por nombre
$app->get('/subjects/search/{name}', 'SubjectController:searchSubjects_ByName');

//TODO: VALIDAR AUTH
//crear materia
$app->post('/subjects', 'SubjectController:createSubject')
        ->add('InputMiddleware:checkData_Subject');


//TODO: VALIDAR AUTH
//cambia estado de materia
//TODO: SEPARAR MÉTODOS: habilitar y deshabilitar
$app->patch('/subjects/{id}/status/{status}', 'SubjectController:changeStatus')
        ->add('InputMiddleware:checkParam_Status')
        ->add('InputMiddleware:checkParam_id');

//TODO: VALIDAR AUTH
//Actualiza materia
$app->put('/subjects/{id}', 'SubjectController:updateSubject')
        ->add('InputMiddleware:checkData_Subject')
        ->add('InputMiddleware:checkParam_id');

//TODO: VALIDAR AUTH
//Elimina materia
$app->delete('/subjects/{id}', 'SubjectController:deleteSubject')
        ->add('InputMiddleware:checkParam_id');


//--------------------------
//  STUDENT ROUTES
//--------------------------

//TODO: VALIDAR AUTH
//Obtiene estudiantes
$app->get('/students', 'StudentController:getStudents');

//TODO: VALIDAR AUTH
//obtiene estudiante por id
$app->get('/students/{id}', 'StudentController:getStudent_ById')
    ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//Busca estudiante por coincidencias (todos los campos de string: nombre, apellido, correo, telefono)
$app->get('/students/search/{search}', 'StudentController:searchStudents')
    ->add('InputMiddleware:checkParam_Search');


//TODO: VALIDAR AUTH
//TODO: Obtener correos tipo gmail para agregar y enviarles correos



//TODO: VALIDAR AUTH
//Actualiza datos de usuario
$app->put('/students/{id}', 'StudentController:updateStudent')
    ->add('InputMiddleware:checkData_Student')
    ->add('InputMiddleware:checkParam_Id');


//--------------------------
//  PERIOD ROUTES
//--------------------------

//TODO: VALIDAR AUTH
//Obtiene periodos
$app->get('/periods', 'PeriodController:getPeriods');

//TODO: VALIDAR AUTH
//TODO: Obtener asesorias de un periodo


//TODO: VALIDAR AUTH
//TODO: periodo activo
$app->get('/periods/current', 'PeriodController:getCurrentPeriod');

//TODO: VALIDAR AUTH
//Obtiene periodo por id
$app->get('/periods/{id}', 'PeriodController:getPeriod_ById')
    ->add('InputMiddleware:checkParam_id');

//TODO: VALIDAR AUTH
//Crea periodo
$app->post('/periods', 'PeriodController:createPeriod')
    ->add('InputMiddleware:checkData_Period');

//TODO: VALIDAR AUTH
//actualiza periodo
$app->put('/periods/{id}', 'PeriodController:updatePeriod')
    ->add('InputMiddleware:checkData_Period')
    ->add('InputMiddleware:checkParam_id');

//TODO: VALIDAR AUTH
//cambia status de periodo
//TODO: SEPARAR MÉTODOS: habilitar y deshabilitar
$app->patch('/periods/{id}/status/{status}', 'PeriodController:changeStatus')
        ->add('InputMiddleware:checkParam_Status')
        ->add('InputMiddleware:checkParam_id');

//TODO: VALIDAR AUTH
//elimina periodo
$app->delete('/periods/{id}', 'PeriodController:deletePeriod')
        ->add('InputMiddleware:checkParam_id');


//--------------------------
//  SCHEDULE, HOURS AND DAYS ROUTES
//--------------------------

//TODO: VALIDAR AUTH
//Obtiene horas y dias disponibles para utilizar
$app->get('/schedule/source', 'ScheduleController:getHoursAndDays');

//TODO: VALIDAR AUTH
//obtiene horario por id
$app->get('/schedule/{id}', 'ScheduleController:getSchedule_ById')
        ->add('InputMiddleware:checkParam_id');

//TODO: VALIDAR AUTH
$app->get('/schedule/adviser/{adviser}/alumn/{alumn}/match', 'ScheduleController:getScheduleMatchHours_ByStudents');
//    ->add('InputMiddleware:checkParam_Id')
//  ;

//Obtiene todos los asesores activos del periodo actual
//$app->get('/schedule/advisers', 'ScheduleController:getCurrentAdvisers')
//    ->add('InputMiddleware:checkParam_id')
//  ;


//TODO: VALIDAR AUTH
//obtiene asesores disponibles (activos) por materia
$app->get('/subject/{id}/advisers', 'SubjectController:getAdvisers_BySubject_IgnoreStudent')
    ->add('InputMiddleware:checkParam_id');

//TODO: obtener asesores disponibles de dicha por materia
//TODO: obtener horario de Asesor y comparar con Solicitante


//TODO: VALIDAR AUTH
//Cambia estado de horario
//TODO: SEPARAR MÉTODOS: habilitar y deshabilitar
$app->patch('/schedule/{id}/status/{status}', 'ScheduleController:changeStatus')
        ->add('InputMiddleware:checkParam_Status')
        ->add('InputMiddleware:checkParam_Id');


//-------------------ESTUDIANTE

//TODO: VALIDAR AUTH
//Obtiene horario actual de estudiante
$app->get('/students/{id}/schedule', 'StudentController:getSchedule_ByStudentId')
    ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
//crea horario (horas)
$app->post('/students/{id}/schedule', 'StudentController:createSchedule')
        ->add('InputMiddleware:checkParam_Id');


//TODO: obtener materias de horario
//TODO: deshabilitar para ser asesor

//TODO: VALIDAR AUTH
//actualiza horas de horario
$app->put('/schedule/{id}/hours', 'ScheduleController:updateScheduleHours')
        ->add('InputMiddleware:checkData_schedule_hours')
        ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
//agrega materias a horario
//$app->post('/schedule/{id}/subjects', 'ScheduleController:addScheduleSubjects')
//        ->add('InputMiddleware:checkData_schedule_subjects')
//        ->add('InputMiddleware:checkParam_Id')
//      ;

//TODO: VALIDAR AUTH
//actualiza materias de horario
$app->put('/schedule/{id}/subjects', 'ScheduleController:updateScheduleSubjects')
        ->add('InputMiddleware:checkData_schedule_subjects')
        ->add('InputMiddleware:checkParam_Id');


//TODO: validacion de materias de horario (admin)

//TODO: agregar: deshabilitar/habilitar horario

//TODO: agregar: eliminar (admin)


//--------------------------
//  ADVISORY ROUTES
//--------------------------
//TODO: debe obtenerse solo materias validadas por admin (status = 2)


//TODO: no debe poder solictarse la misma materia mientras una asesoria este activa con la misma


//TODO: Al final del periodo, debe finalizarse todas las asesorias activas


//TODO: VALIDAR AUTH
//Obtiene asesorias actuales
$app->get('/advisories', 'AdvisoryController:getAdvisories');


//TODO: VALIDAR AUTH
//Obtiene asesorias de estudiante
$app->get('/student/{id}/advisories', 'StudentController:getCurrentAdvisories_ByStudentId')
    ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//Obtiene asesorias de estudiante donde es alumno
$app->get('/student/{id}/advisories/requested', 'StudentController:getCurrentAdvisories_Requested')
    ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//Obtiene asesorias de estudiante donde es alumno
$app->get('/student/{id}/advisories/adviser', 'StudentController:getCurrentAdvisories_Adviser')
    ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//Obtiene horas de asesoria asignada
$app->get('/advisories/{id}/hours', 'AdvisoryController:getAdvisoryHours_ById')
    ->add('InputMiddleware:checkParam_Id');


//--------------ADVISORIES
//TODO: VALIDAR AUTH
//Obtiene asesoria por id
$app->get('/advisories/{id}', 'AdvisoryController:getAdvisory_ById')
        ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
$app->get('/subjects/{id}/advisers/ignore/{student}', 'SubjectController:getAdvisers_BySubject_IgnoreStudent')
    ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
//crear asesoria por estudiante
$app->post('/students/{id}/advisories', 'AdvisoryController:createStudentAdvisory')
        ->add('InputMiddleware:checkData_advisory')
        ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
$app->get('/advisories/{id}/schedule', 'AdvisoryController:getAdvisorySchedule')
    ->add('InputMiddleware:checkParam_Id');


//TODO: VALIDAR AUTH
$app->patch('/advisories/{id}/finalize', 'AdvisoryController:finalizeAdvisory')
    ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
$app->post('/advisories/{id}/assign', 'AdvisoryController:assignAdviser')
    ->add('InputMiddleware:checkData_advisory_schedule')
    ->add('InputMiddleware:checkParam_Id');

//TODO: VALIDAR AUTH
//$app->put('/advisories/{id}/hours', 'AdvisoryController:updateAdvisoryHours');





try{
    $app->run();
} catch (MethodNotAllowedException $e) {
//    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
} catch (NotFoundException $e) {
//    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
} catch (\Exception $e) {
//    throw new InternalErrorException("Index", "Slim error",  $e->getMessage());
}