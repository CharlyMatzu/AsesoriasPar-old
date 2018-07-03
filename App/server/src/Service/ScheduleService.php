<?php namespace App\Service;

use App\Auth;
use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NoContentException;
use App\Exceptions\Request\NotFoundException;
use App\Exceptions\Persistence\TransactionException;
use App\Exceptions\Request\RequestException;
use App\Persistence\Persistence;
use App\Persistence\SchedulesPersistence;
use App\Model\ScheduleModel;
use App\Utils;

class ScheduleService{

    private $schedulesPer;

    public function __construct(){
        $this->schedulesPer = new SchedulesPersistence();
    }

    /**
     * @param $id int
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getSchedule_ById($id)
    {
        $result = $this->schedulesPer->getSchedule_Byid($id);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getSchedyle_ById","Error al obtener horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe horario");

        return $result->getData()[0];
    }

    /**
     * @param $id int
     *
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws RequestException
     */
    public function getFullSchedule_ById($id)
    {
        $result = $this->schedulesPer->getSchedule_Byid($id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getSchedule_ById","Error al obtener horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe horario");

        //Adiciona información extra
        $schedule = $result->getData()[0];
        return $this->setScheduleData_ById( $schedule );
    }


    /**
     *
     * @param $studentId int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentSchedule_ByStudentId($studentId)
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        //--------Comprobando si existe alumno
        $result = $this->schedulesPer->getSchedule_ByStudentId_Period($studentId, $period['id']);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentSchedule_ByStuID",
                "Error al obtener horario actual de alumno", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("Alumno no tiene horario en periodo actual");

        return $result->getData()[0];
    }


    /**
     * @param $schedule array|\mysqli_result
     *
     * @return array
     * @throws RequestException
     * @throws \App\Exceptions\Request\UnauthorizedException
     */
    public function setScheduleData_ById( $schedule )
    {

        $schedule = ScheduleService::makeScheduleModel( $schedule );
        //se obtiene horas de horario
        $hours_days = array();
        try {
            //Obtiene solo horas activas
            $hours_days = $this->getScheduleHours_BySchedule_Enabled( $schedule->getId() );
            //Si no tiene horas, no hay problema
        }catch (InternalErrorException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
            //Si no hay horas, no hay problema
        }catch (NoContentException $e){}

        //se obtiene materias (si hay)
        $subjects = array();
        try {

            //---------AUTHENTICATION
            //Obtiene todas las materias si es staff, si es estudiante, solo las activas
            $subjects = [];
            if( Auth::$isSessionON ){
                if( Auth::isStaffUser() )
                    $subjects = $this->getScheduleSubjects_BySchedule( $schedule->getId() );
                else
                    $subjects = $this->getScheduleSubjects_BySchedule_Enabled( $schedule->getId() );
            }
            else
                $subjects = $this->getScheduleSubjects_BySchedule( $schedule->getId() );


        }catch (InternalErrorException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
            //Si no tiene materias, no hay problema
        }catch (NoContentException $e){}

        $full_schedule = [
            "id" => $schedule->getId(),
            "status" => $schedule->getStatus(),
            "period" => $schedule->getPeriod(),
            "days_hours" => $hours_days,
            "subjects" => $subjects
        ];

        return $full_schedule;
    }


    /**
     * Obtiene días existentes para utilizar
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getDays(){

        $result = $this->schedulesPer->getDays();
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException('getDays', "Error al obtener días");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No se encontraron días");

        return $result->getData();
    }


    /**
     * @return array
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getDaysAndHours(){
        $result = $this->schedulesPer->getDaysAndHours( SchedulesPersistence::ORDER_BY_DAY );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class,"Error al obtener dias y horas", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $this->formatScheduleHours($result->getData());
    }

    /**
     * @param $id int
     *
     * @return \mysqli_result|array|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getScheduleHours_BySchedule($id )
    {

        $result = $this->schedulesPer->getScheduleHours_ByScheduleId( $id, SchedulesPersistence::ORDER_BY_DAY );


        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getScheduleHours_ById","Error al obtener dias y horas de horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $this->formatScheduleHours($result->getData());
    }

    /**
     * @param $id int
     * @return \mysqli_result|array|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getScheduleHours_BySchedule_Enabled($id)
    {
        $result = $this->schedulesPer->getScheduleHours_ByScheduleId_Enabled( $id, SchedulesPersistence::ORDER_BY_DAY );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getScheduleHours_ById","Error al obtener dias y horas de horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $this->formatScheduleHours($result->getData());
    }

    /**
     * @param $id int
     *
     * @return mixed
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws NotFoundException
     */
    public function getAvailableSubjects_BySchedule($id)
    {
        //Se verifica horario
        $this->getSchedule_ById( $id );

        //Se obtienen materias disponibles
        $subjServ = new SubjectService();
        $subjects = $subjServ->getEnabledSubjects();

//        $result = $this->schedulesPer->getScheduleSubjects_BySchedule_Enabled( $id );
        $result = $this->schedulesPer->getScheduleSubjects_BySchedule( $id );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getAvailableSubjects_BySchedule",
                "Error al obtener materias disponibles", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            return $subjects;

        //Se obtienen datos
        $scheduleSubjects = $result->getData();

        //-----Verificando materias disponibles
        $availables = array();
        //Se recorren las materias
        foreach ( $subjects as $s ){
            $add = true;
            //Se recorren las materias del horario
            foreach ( $scheduleSubjects as $ss ){
                //Si es la misma materia
                if( $ss['subject_id'] === $s['id'] ){
                    //Si la materia del horario esta deshabilitada, se agrega a mis materias disponibles
                    if( $ss['status'] !== Utils::$STATUS_DISABLE )
                        $add = false;
                    //Se termina ciclo
                    break;
                }
            }
            //Si add es true, se agrega
            if( $add )
                $availables[] = $s;
        }

        if( empty( $availables ) )
            throw new NoContentException("No hay materias disponibles");

        return $availables;
    }


    /**
     * @param $id
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getScheduleSubjects_BySchedule($id)
    {
        $result = $this->schedulesPer->getScheduleSubjects_BySchedule( $id );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getScheduleSubjects_ById",
                "Error al obtener materias de horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $result->getData();
    }

    /**
     * @param $id
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getScheduleSubjects_BySchedule_Enabled($id)
    {
        $result = $this->schedulesPer->getScheduleSubjects_BySchedule_Enabled( $id );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getScheduleSubjects_ById_Enabled",
                "Error al obtener materias de horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $result->getData();
    }


    /**
     * @param $subject_id int
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentAdvisers_BySubject($subject_id)
    {
        $periodServ = new PeriodService();
        $period = $periodServ->getCurrentPeriod();

        $result = $this->schedulesPer->getAdvisers_BySubject_ByPeriod( $subject_id, $period['id'] );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentAdvisers_BySubject",
                "Error al obtener asesores por materias por periodo",  $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }


    /**
     * @param $adviser_id int
     * @param $alumn_id int
     *
     * @return array
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws NotFoundException
     */
    public function getCurrentScheduleMatch_ByStudentsId($adviser_id, $alumn_id){

        //Verificamos existencia de estudiantes
        $studenServ = new StudentService();
        $studenServ->getStudent_ById($adviser_id);
        $studenServ->getStudent_ById($alumn_id);

        //se obtiene horario de asesor
        $adviser_schedule = $this->getCurrentSchedule_ByStudentId( $adviser_id );
        $adviser_hours = $this->getScheduleHours_BySchedule( $adviser_schedule['id'] );
        //Se obtiene horario de alumno
        $alumn_schedule = $this->getCurrentSchedule_ByStudentId( $alumn_id );
        $alumn_hours = $this->getScheduleHours_BySchedule( $alumn_schedule['id'] );

        $result = $this->checkScheduleHoursMatch($adviser_hours, $alumn_hours);
        if( empty($result) )
            throw new NoContentException();

        return $result;
    }

    /**
     * @param $ad_hours array Horario de Asesor
     * @param $alu_hours array Horario de alumno
     *
     * @return array
     */
    public function checkScheduleHoursMatch($ad_hours, $alu_hours){
        $match = [];

        //Recorre horario de asesor
        foreach( $ad_hours as $adviser ){
            foreach ($adviser['hours'] as $d) {

                //Recorre horario de alumno
                foreach ( $alu_hours as $al ){
                    foreach ( $al['hours'] as $a ){
                        //Si hacen match, se agrega al array
                        if( $d['day_hour_id'] == $a['day_hour_id'] )
                            $match[] = $d;
                    }
                }
            }
        }

        return $match;
    }




    /**
     * @param $studentId int
     *
     * @throws RequestException
     */
    public function insertSchedule($studentId)
    {

        //Se obtiene periodo actual
        $periodService = new PeriodService();
        $period = null;
        try{
            $period = $periodService->getCurrentPeriod();
        }catch (NoContentException $e){
            throw new ConflictException( $e->getMessage() );
        }

        //verificar que no tenga periodo registrado
        try{
            //se obtiene horario
            $this->getCurrentSchedule_ByStudentId( $studentId );
            //Si tiene horario, entonces se lanza la excepción
            throw new ConflictException("Alumno ya tiene horario");

        }catch (InternalErrorException $e){
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );

        }catch (NoContentException $e){

            //Si no tiene horario, se registra
            $result = $this->schedulesPer->insertSchedule( $studentId, $period['id'] );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException(static::class,"No se pudo registrar horario", $result->getErrorMessage());

        }

    }


    /**
     * @param $schedule_id int
     * @param $newHours array
     *
     * @throws InternalErrorException
     */
    public function insertScheduleHours($schedule_id, $newHours){

        //Se obtiene horas actuales
        $hours = array();
        try{
            $hours = $this->getScheduleHours_BySchedule( $schedule_id );
        }catch (NoContentException $e){}


        foreach ($newHours as $h ){
            //Si no existe, se agrega
            if( !$this->isHoursInArray($h, $hours) ){
                $result = $this->schedulesPer->insertScheduleHours( $schedule_id, $h );
                if( Utils::isError( $result->getOperation() ) )
                    throw new InternalErrorException(static::class, "Error al registrar horas de horario", $result->getErrorMessage());
            }
            //Si ya existe, no se hace nada
        }
    }

    /**
     * @param $schedule_id int
     * @param $oldSubjects
     * @param $newSubjects array
     *
     * @throws InternalErrorException
     */
    private function insertScheduleSubjects($schedule_id, $oldSubjects, $newSubjects)
    {
        //TODO: Cuando sea registrado, se debe enviar correo a admin avisando de un nuevo asesor
        
        foreach ($newSubjects as $sub){
            //Se comparan materias viejas con nuevas y solo se agregan las nuevas, las otras son ignoradas
            if ( !$this->isSubjectInArray($sub, $oldSubjects) ) {
                //Se registra
                $result = $this->schedulesPer->insertScheduleSubjects($schedule_id, $sub);
                if (Utils::isError($result->getOperation()))
                    throw new InternalErrorException("insertScheduleSubjects", "Error al registrar materia de horario", $result->getErrorMessage());
            }
        }
    }

//    /**
//     * @param $schedule_id int
//     * @param $newSubjects array
//     *
//     * @throws InternalErrorException
//     * @throws RequestException
//     */
//    public function insertScheduleSubjects($schedule_id, $newSubjects){
//
//        //Verifica que exista horario
//        $this->getSchedule_ById( $schedule_id );
//        //TODO: si horario esa deshabilitado o ya paso el periodo, no debe poder modificarse
//
//        try {
//            Persistence::initTransaction();
//        } catch (TransactionException $e) {
//            throw new InternalErrorException("InsertScheduleSubjects", $e->getMessage());
//        }
//
//
//        $subjects = array();
//        try{
//            $subjects = $this->getScheduleSubjects_Byid($schedule_id);
//        }catch (InternalErrorException $e){
//            try {
//                Persistence::rollbackTransaction();
//            } catch (TransactionException $e) {
//                throw new InternalErrorException("InsertScheduleSubjects", $e->getMessage());
//            }
//            throw new InternalErrorException("InsertScheduleSubjects", "Se detuvo inserción de materias");
//        }catch (NoContentException $e){}
//
//
//        foreach ($newSubjects as $sub ){
//
//            //TODO: Comprueba si materia existe
////            $subjectService = new SubjectService();
////            try{
////                $subjectService->getSubject_ById( $sub );
////            }catch (RequestException $e){
////                Persistence::rollbackTransaction();
////                throw new RequestException( $e->getMessage(), $e->getStatusCode() );
////            }
//
//            //Si la materia no existe en el horario actual, se agrega
//            if( !$this->isSubjectInArray($sub, $subjects) ){
//
//                //TODO: Cuando sea registrado, se debe enviar correo a admin avisando de un nuevo asesor
//                //TODO: cada nueva materia debe ponerse en estatus de no confirmado
//
//                $result = $this->schedulesPer->insertScheduleSubjects( $schedule_id, $sub );
//                if( Utils::isError( $result->getOperation() ) ) {
//                    try {
//                        Persistence::rollbackTransaction();
//                    } catch (TransactionException $e) {
//                        throw new InternalErrorException("InsertScheduleSubjects", $e->getMessage());
//                    }
//                    throw new InternalErrorException("insertScheduleSubjects", "Error al registrar materia de horario", $result->getErrorMessage());
//                }
//
//            }
//        }
//
//        try {
//            Persistence::commitTransaction();
//        } catch (TransactionException $e) {
//            throw new InternalErrorException("InsertScheduleSubjects", $e->getMessage());
//        }
//    }




//    //------------------------------
//    // FUNCIONES ADICIONALES
//    //------------------------------


    /**
     * @param $s \mysqli_result
     *
     * @return ScheduleModel
     */
    public static function makeScheduleModel( $s ){

        $schedule = new ScheduleModel();
        $schedule->setId( $s['id'] );
        $schedule->setPeriod( $s['period_id'] );
        $schedule->setStudent( $s['student_id'] );
        $schedule->setRegisterDate( $s['date_register'] );
        $schedule->setStatus( $s['status'] );

        return $schedule;
    }


    public static function makeHoursAndDaysArray($hd){
        $hoursAndDays = [
            'id'  => $hd['id'],
            'day'  => $hd['day'],
            'hour' => $hd['hour']
        ];
        return $hoursAndDays;
    }

    /**
     * @param $scheduleId int
     * @param $newHours array
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws TransactionException
     */
    public function updateScheduleHours($scheduleId, $newHours)
    {
        //Comprobando si existe horario
        $this->getSchedule_ById( $scheduleId );

        //Se debe agregar las nuevas horas
        // las que ya no estan deben deshabilitarse
        //Si hay asesorías activas asociadas a esa hora, deben finalizarse y notificar a admin y a alumnos

        //---Se obtienen horas y dias
        $days_hours = array();
        try{
            $days_hours = $this->getScheduleHours_BySchedule( $scheduleId );
            //si esta vacío, no hay problema
        }catch (NoContentException $e){}

        try {
            Persistence::initTransaction();
        } catch (TransactionException $e) {
            throw new InternalErrorException("updateScheduleHours", $e->getMessage());
        }


        //Se comparan cuales ya no están para deshabilitar
        //NOTA: se le puede poner un try/catch para que continue a pesar del error

        try{
            //Recorre cada dia
            foreach ( $days_hours as $days ){
                //Recorre las horas
                foreach( $days['hours'] as $hour ){
                    //Si la hora que esta actualmente en el horario, no se encuentra en la update, se deshabilita
                    if( !in_array($hour['day_hour_id'], $newHours) ){
                        $this->changeStatus_ScheduleHour( $hour['id'], Utils::$STATUS_DISABLE );
                    }
                    //si ya existe, se habilita
                    else{
                        $this->changeStatus_ScheduleHour( $hour['id'], Utils::$STATUS_ACTIVE );
                    }
                }
            }
        }catch (RequestException $e){
            try {
                Persistence::rollbackTransaction();
            } catch (TransactionException $e) {
                throw new InternalErrorException("updateScheduleHours", $e->getMessage());
            }
            throw new InternalErrorException("updateScheduleHours", $e->getMessage());
        }

        //Se registran horas
        try{
            $this->insertScheduleHours( $scheduleId, $newHours );
        }catch (RequestException $e){
            Persistence::rollbackTransaction();
            throw new InternalErrorException("updateScheduleHours",
                "Se detuvo actualización de horas de horario", $e->getMessage());
        }


        try {
            Persistence::commitTransaction();
        } catch (TransactionException $e) {
            throw new InternalErrorException("updateScheduleHours", $e->getMessage());
        }

    }

    /**
     * @param $scheduleId int
     * @param $status int
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($scheduleId, $status)
    {
        $this->getSchedule_ById( $scheduleId );

        $result = $this->schedulesPer->changeStatus( $scheduleId, $status );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("changeStatus", "Error al cambiar status de horario", $result->getErrorMessage() );
    }


    /**
     * @param $scheduleId int
     * @param $newSubjects array
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws TransactionException
     * @throws RequestException
     */
    public function updateScheduleSubjects($scheduleId, $newSubjects)
    {
        //Comprobando si existe horario
        try{
            $this->getSchedule_ById( $scheduleId );
        }catch (InternalErrorException $e){
            throw new InternalErrorException("updateScheduleSubjects",
                "se detuvo actualización de materias", $e->getMessage());
        }

        //Se debe agregar las nuevas materias
        // las que ya no estan deben deshabilitarse
        //Si hay asesorías activas asociadas a esa materia, deben finalizarse y notificar a admin y a alumnos

        //---Se obtienen horas y dias
        $subjects = array();
        try{
            $subjects = $this->getScheduleSubjects_BySchedule( $scheduleId );
        }catch (InternalErrorException $e){
            throw new InternalErrorException("updateScheduleSubjects",
                "se detuvo actualización de materias", $e->getMessage());

            //No hay problema
        }catch (NoContentException $e){}

        try {
            Persistence::initTransaction();
        } catch (TransactionException $e) {
            throw new InternalErrorException("updateScheduleSubjects", $e->getMessage());
        }


        //Se comparan cuales ya no están para deshabilitar
        //NOTA: se le puede poner un try/catch para que continue a pesar del error

        try{
            //Ciclo para deshabilitar o habilitar dependiendo de las nuevas materias
            foreach ( $subjects as $sub ){
                
                //Si la hora que esta actualmente en el horario, no se encuentra en la update, se deshabilita
                if( !in_array($sub['subject_id'], $newSubjects) ){
                    //Si la materia esta bloqueada por el admin no se podrá deshabilitar
                    if( $sub['status'] !== Utils::$STATUS_LOCKED )
                        $this->changeStatus_ScheduleSubject( $sub['id'], Utils::$STATUS_DISABLE );
                }
                //si ésta ya existe, se pone como pendiente
                else{
                    //FIXME: se debe tener cuidado si la materia aun no ha sido validada para no habilitarla
                    $this->changeStatus_ScheduleSubject( $sub['id'], Utils::$STATUS_PENDING );
                }
            }
        }catch (RequestException $e){
            try {
                Persistence::rollbackTransaction();
            } catch (TransactionException $e) {
                throw new InternalErrorException("updateScheduleSubjects", $e->getMessage());
            }
            throw new InternalErrorException("updateScheduleSubjects", $e->getMessage());
        }


        //Se registran materias nuevas
        try{
            $this->insertScheduleSubjects( $scheduleId, $subjects, $newSubjects );
        }catch (RequestException $e){
            Persistence::rollbackTransaction();
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }

        //Se registra transacción
        Persistence::commitTransaction();
    }


    //-----------------HORAS

    /**
     * @param $sc_hourId
     * @param $status
     *
     * @throws InternalErrorException
     */
    private function changeStatus_ScheduleHour($sc_hourId, $status )
    {
        //TODO: notificar a usuarios asociados al ser desactivado y deshabilitar asesorías asociadas
        $result = $this->schedulesPer->changeStatus_ScheduleHour($sc_hourId, $status);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "disableHour",
                "Error al deshabilitar hora de horario: $sc_hourId", $result->getErrorMessage() );

    }

//    /**
//     * @param $hourId int
//     *
//     * @throws InternalErrorException
//     */
//    private function disableHour($hourId)
//    {
//        //TODO: notificar a usuarios asociados
//        $result = $this->schedulesPer->changeStatus_ScheduleHour($hourId, Utils::$STATUS_DISABLE);
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( "disableHour",
//                "Error al deshabilitar hora de horario: $hourId", $result->getErrorMessage() );
//
//    }
//
//    /**
//     * @param $hourId INT
//     *
//     * @throws InternalErrorException
//     */
//    private function enableHour($hourId)
//    {
//        $result = $this->schedulesPer->changeStatus_ScheduleHour($hourId, Utils::$STATUS_ACTIVE);
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( "enableHour",
//                "Error al habilitar hora de horario: $hourId", $result->getErrorMessage() );
//    }


    /**
     * @param $subject_id
     * @param $schedule_id
     *
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getScheduleSubject_BySubject( $subject_id, $schedule_id )
    {
        $result = $this->schedulesPer->getScheduleSubject_BySubject_BySchedule( $subject_id, $schedule_id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "getScheduleSubject_BySubject",
                "Error al habilitar hora de horario:", $result->getErrorMessage() );
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No se encontró materia");
        else
            return $result->getData()[0];
    }


    //------------------MATERIAS


    /**
     * @param $schedule_id
     * @param $subject_id
     * @param $status
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function validateScheduleSubject_BySchedule($schedule_id, $subject_id, $status){
        //Verificamos horario
        $this->getSchedule_ById( $schedule_id );

        //TODO: verificamos materia

        $this->changeStatus_ScheduleSubject( $subject_id, $status );
    }

    /**
     * @param $subId int
     * @param $status int
     * @throws InternalErrorException
     */
    public function changeStatus_ScheduleSubject($subId, $status)
    {

        //TODO: si tiene solicitudes de asesoría de dicha materia, no debe permitirse

        $result = $this->schedulesPer->changetStatus_ScheduleSubject( $subId, $status);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "disableSubject",
                "Error al deshabilitar materia de horario: $subId", $result->getErrorMessage() );

    }

//    /**
//     * @param $subId int
//     *
//     * @throws InternalErrorException
//     */
//    private function disableSubjec($subId)
//    {
//        //TODO: notificar a usuarios asociados
//        $result = $this->schedulesPer->changetStatus_ScheduleSubject($subId, Utils::$STATUS_DISABLE);
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( "disableSubject",
//                "Error al deshabilitar materia de horario: $subId", $result->getErrorMessage() );
//
//    }
//
//    /**
//     * @param $subId int
//     * @throws InternalErrorException
//     */
//    private function validateSubjec($subId)
//    {
//        //TODO: notificar a usuarios asociados
//        $result = $this->schedulesPer->changetStatus_ScheduleSubject($subId, Utils::$STATUS_VALIDATED);
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( "disableSubject",
//                "Error al deshabilitar materia de horario: $subId", $result->getErrorMessage() );
//
//    }

//    /**
//     * @param $subId int
//     * @throws InternalErrorException
//     */
//    private function validateSubjec($subId)
//    {
//        //TODO: notificar a usuarios asociados
//        $result = $this->schedulesPer->changetStatus_ScheduleSubject($subId, Utils::$STATUS_VALIDATED);
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( "disableSubject",
//                "Error al deshabilitar materia de horario: $subId", $result->getErrorMessage() );
//
//    }

//    /**
//     * @param $subId INT
//     *
//     * @throws InternalErrorException
//     */
//    private function enableSubject($subId)
//    {
//        $result = $this->schedulesPer->changetStatus_ScheduleSubject($subId, Utils::$STATUS_ACTIVE);
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException( "enableSubject",
//                "Error al habilitar materia de horario: $subId", $result->getErrorMessage() );
//    }

    /**
     * @param $subject_id int
     * @param $subjects array|\mysqli_result
     *
     * @return bool
     */
    private function isSubjectInArray($subject_id, $subjects)
    {
        //Si coinciden, es que si existe
        foreach ( $subjects as $s ){
            if( $s['subject_id'] == $subject_id )
                return true;
        }
        return false;
    }



    /**
     * @param $hour_id int
     * @param $hours array
     *
     * @return bool
     */
    private function isHoursInArray($hour_id, $hours)
    {
        //Si coinciden, es que si existe
        foreach ($hours as $data ){
            foreach ( $data['data'] as $hour ){
                if( $hour['day_hour_id'] == $hour_id )
                    return true;
            }
        }
        return false;
    }

    /**
     * Se encarga de juntar las horas correspondientes a cada día, esto funciona gracias al valor "day_number"
     * que hace referencia al número del dia (Lunes = 1, Martes = 2....) y al estar ordenados de esta forma sólo es necesario
     * hacer un solo recorrido
     *
     * @param $schedule_hours array|\mysqli_result corresponde a las horas de un horario
     *
     * @return array
     * @throws InternalErrorException
     */
    public function formatScheduleHours( $schedule_hours ){
        //Obtiene días existentes individualmente (ordenados)
        $daysArray = $this->schedulesPer->getDays()->getData();
        //Array vacío para rellenar de valores
        $formatedSchedule = array();
        $index = 0;

        //Se recorren los días
        foreach( $daysArray as $day ){
            //array para horas
            $schedule_day_hour = array();
            $continue = true;

            //Se recorre horas "sueltas" de horario
            //NOTA: se usa un while normal para no regresar al inicio del array
            while( $index < count($schedule_hours) && $continue ){
                //Si es el mismo día
                if( $schedule_hours[$index]['day'] === $day['day'] ){
                    $schedule_day_hour[] = [
                        "id" => $schedule_hours[$index]['id'],
                        "day_hour_id" => $schedule_hours[$index]['day_hour_id'],
                        "hour" => $schedule_hours[$index]['hour']
                    ];

                    //Se incrementa contador
                    $index++;
                }
                //Si no es, se rompe foreach
                else
                    $continue = false;

            }//end hours foreach

            //se agregan datos a array
            $formatedSchedule[] = [
                "day" => $day['day'],
                "day_number" => $day['day_number'],
                "hours" => $schedule_day_hour
            ];
        }//end days foreach


        return $formatedSchedule;
    }





//    /**
//     * Se encarga de juntar las horas correspondientes a cada día, esto funciona gracias al valor "day_number"
//     * que hace referencia al número del dia (Lunes = 1, Martes = 2....) y al estar ordenados de esta forma sólo es necesario
//     * hacer un solo recorrido
//     * @param $schedule array|\mysqli_result
//     *
//     * @return array
//     * @throws InternalErrorException
//     */
//    public function formatScheduleHours($schedule ){
//        //Obtiene días existentes individualmente (ordenados)
//        $daysArray = $this->schedulesPer->getDays()->getData();
//        //Array vacío para rellenar de valores
//        $formatedSchedule = array();
//        //Corresponde a cada día
//        $index = 0;
//
//        //Se recorre cada día y a partir de este, se lee el horario
//        foreach ( $daysArray as $day ){
//
//            //se recorre el array para encontrar los similares
//            $schedule_day_hour = array();
//            $continue = true;
//
//            //Recorre todas las horas registradas para un horario
//            for( ; $continue && $index < count($schedule); ){
//                //Si el dia (primer foreach) es igual al del array, entonces pertenece al dia y se agrega
//                if( $schedule[$index]['day'] === $day['day'] ) {
//                    //Si son el mismo día/hora
//                    if( isset( $schedule[$index]['day_hour_id'] ) ){
//                        //Se crea array de hora
//                        $schedule_day_hour[] = [
//                            "id" => $schedule[$index]['id'],
//                            "day_hour_id" => $schedule[$index]['day_hour_id'],
//                            "hour" => $schedule[$index]['hour']
//                        ];
//                    }
//                    else{
//                        $schedule_day_hour[] = [
//                            "id" => $schedule[$index]['id'],
//                            "hour" => $schedule[$index]['hour']
//                        ];
//                    }
//
//                    //Aumenta contador
//                    $index++;
//                }
//                else{
//                    //detiene ciclo
//                    $continue = false;
//                }
//            }//end foreach
//            //Se agrega acumulado al schedule principal
//            $formatedSchedule[] = [
//                "day" => $day['day'],
//                "day_number" => $day['day_number'],
//                "data" => $schedule_day_hour
//            ];
//        } //end foreach
//        return $formatedSchedule;
//    }




}