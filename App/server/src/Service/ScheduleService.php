<?php namespace App\Service;


use App\AppLogger;
use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;
use App\Persistence\SchedulesPersistence;
use App\Model\Schedule;
use App\Utils;
use Monolog\Logger;
use PHPMailer\PHPMailer\Exception;

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
            throw new InternalErrorException(static::class.":getSchedyle_ById","Error al obtener horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe horario");

        return $result->getData()[0];
    }

    /**
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
            throw new InternalErrorException(static::class.":getCurrentSchedule_ByStuID",
                "Error al obtener horario actual de alumno", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("Alumno no tiene horario en periodo actual");

        return $result->getData()[0];
    }



    public function getDays(){
        $result = $this->schedulesPer->getDays();
        if( !is_array($result) )
            return $result;
        else{
            $days = array();
            foreach( $result as $day ){
                $days[] = $day['day'];
            }
            return $days;
        }
    }

    /**
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getHoursAndDays(){
        $result = $this->schedulesPer->getHoursAndDays( SchedulesPersistence::ORDER_BY_DAY );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class,"Error al obtener dias y horas", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $result->getData();
    }

    /**
     * @param $id int
     * @return \mysqli_result|array|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getScheduleHoursAndDays_ById($id)
    {
        $result = $this->schedulesPer->getScheduleHours_ByScheduleId( $id, SchedulesPersistence::ORDER_BY_DAY );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class,"Error al obtener dias y horas de horario", $result->getErrorMessage());
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
    public function getScheduleSubjects_ById($id)
    {
        $result = $this->schedulesPer->getScheduleSubjects_ById( $id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class,"Error al obtener materias de horario", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $result->getData();
    }


    /**
     * @param $studentId int
     * @param $schedule_hours array
     *
     * @throws RequestException
     */
    public function insertSchedule($studentId, $schedule_hours)
    {

        //Se obtiene periodo actual
        $periodService = new PeriodService();
        $period = null;
        try{
            $period = $periodService->getCurrentPeriod();
        }catch (NoContentException $e){
            throw new ConflictException( $e->getMessage() );
        }

        //vericicar que no tenga periodo registrado
        try{
            //se obtiene horario
            $this->getCurrentSchedule_ByStudentId( $studentId );
            //Si tiene horario, entonces se lanza la excepcion
            throw new ConflictException("Alumno ya tiene horario");

        }catch (InternalErrorException $e){
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );

        }catch (NoContentException $e){

            //----------COMIENZA TRANSACCION
            $trans = SchedulesPersistence::initTransaction();
            if( !$trans )
                throw new InternalErrorException(static::class."InsertSchedule", "Error al iniciar transaccion");

            //Si no tiene horario, se registra
            $result = $this->schedulesPer->insertSchedule( $studentId, $period['id'] );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException(static::class,"No se pudo registrar horario", $result->getErrorMessage());


            //Se obtiene horario de alumno en periodo actual
            $current_schedule = $this->getCurrentSchedule_ByStudentId( $studentId );

            //TODO: comprobar que horas existen
            //Se registran horas
            $this->insertScheduleHours( $current_schedule['id'], $schedule_hours );

            //Se guardan los registros
            $trans = SchedulesPersistence::commitTransaction();
            if( !$trans )
                throw new InternalErrorException(static::class.":InsertSchedule", "Error al registrar transaccion");
            //----------FIN TRANSACCION
        }

    }


    /**
     * @param $scheduleid int
     * @param $newHours array
     *
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function insertScheduleHours($scheduleid, $newHours){

        //Se obtiene horas actuales
        $hours = $this->getScheduleHoursAndDays_ById( $scheduleid );

        foreach ($newHours as $h ){
            //Si no exsiste, se agrega
            if( !$this->isHoursInArray($h, $hours) ){
                $result = $this->schedulesPer->insertScheduleHours( $scheduleid, $h );
                if( Utils::isError( $result->getOperation() ) )
                    throw new InternalErrorException(static::class, "Error al registrar horas de horario", $result->getErrorMessage());
            }
            //Si ya exite, no se hace nada
        }
    }

    /**
     * @param $scheduleid int
     * @param $newSubjects array
     *
     * @throws InternalErrorException
     * @throws RequestException
     * TODO: mover la transaccion hacia metodo padre
     */
    public function insertScheduleSubjects($scheduleid, $newSubjects){
        //TODO: verificar que materia no este registrada en horario

        if( !SchedulesPersistence::initTransaction() )
            throw new InternalErrorException(static::class."InsertScheduleSubjects", "Error al iniciar tranasaccion");

        $subjects = array();
        try{
            $subjects = $this->getScheduleSubjects_ById($scheduleid);
        }catch (InternalErrorException $e){
            SchedulesPersistence::rollbackTransaction();
            throw new InternalErrorException(static::class."InsertScheduleSubjects", "Se detuvo insercion de materias");
        }

        $subjectService = new SubjectService();
        foreach ($newSubjects as $sub ){

            //TODO: Comprueba si materia existe
//            try{
//                $subjectService->getSubject_ById( $sub );
//            }catch (RequestException $e){
//                SchedulesPersistence::rollbackTransaction();
//                throw new RequestException( $e->getMessage(), $e->getStatusCode() );
//            }

            //Si la materia no existe en el horario actual, se agrega
            if( !$this->isSubjectInArray($sub, $subjects) ){
                //TODO: Cuando sea registrado, se debe enviar correo a admin avisando de un nuevo asesor
                //TODO: cada nueva materia debe ponerse en estatus de no confirmado
                $result = $this->schedulesPer->insertScheduleSubjects( $scheduleid, $sub );
                if( Utils::isError( $result->getOperation() ) ) {
                    SchedulesPersistence::rollbackTransaction();
                    throw new InternalErrorException(static::class."insertScheduleSubjects", "Error al registrar materia de horario", $result->getErrorMessage());
                }
            }
        }

        if( !SchedulesPersistence::commitTransaction() )
            throw new InternalErrorException(static::class."InsertScheduleSubjects","Error al registrar tranasaccion");
    }


//    public function getCurrAvailSchedules_SkipStudent($subjectId, $studentId){
//        $cycle = $this->getCurrentPeriod();
//        if( !is_array($cycle) )
//            return $cycle;
//        else{
//            $result = $this->schedulesPer->getAvailSchedules_SkipStudent_ByPeriod( $subjectId, $studentId, $cycle['id'] );
//            if( $result == false )
//                return 'error';
//            else
//                return $result;
//        }
//
//    }




//    //------------------------------
//    // FUNCIONES ADICIONALES
//    //------------------------------


    /**
     * @param $s \mysqli_result
     * @return Schedule
     */
    public static function makeScheduleModel( $s ){

        $schedule = new Schedule();
        $schedule->setId( $s['id'] );
        $schedule->setPeriod( $s['period_id'] );
        $schedule->setStudent( $s['student_id'] );
        $schedule->setRegisterDate( $s['register_date'] );
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
     * @throws NoContentException
     */
    public function updateScheduleHours($scheduleId, $newHours)
    {
        //Comprobando si existe horario
        $this->getSchedule_ById( $scheduleId );

        //Se debe agregar las nuevas horas
        // las que ya no estan deben deshabilitarse
        //Si hay asesorias activas asociadas a esa hora, deben finalizarse y notificar a admin y a alumnos

        //---Se obtienen horas y dias
        $hours = $this->getScheduleHoursAndDays_ById( $scheduleId );

        $trans = SchedulesPersistence::initTransaction();
        if( !$trans )
            throw new InternalErrorException(static::class.":updateScheduleHours", "Error al iniciar transaccion");

        //Se comparan cuales ya no estan para deshabilitar
        //NOTA: se le puede poner un try/catch para que continue a pesar del error

        try{
            foreach ( $hours as $hu ){
                //Si la hora que esta actualmente en el horario, no se encuentra en la update, se deshabilita
                if( !in_array($hu['day_hour_id'], $newHours) ){
                    $this->disableHour($hu['id']);
                }
                //si exta ya existe, se habilita
                else{
                    $this->enableHour($hu['id']);
                }
            }
        }catch (RequestException $e){
            SchedulesPersistence::rollbackTransaction();
            throw new InternalErrorException(static::class.":updateScheduleHours", $e->getMessage());
        }

        //Se registran horas
        try{
            $this->insertScheduleHours( $scheduleId, $newHours );
        }catch (RequestException $e){
//            SchedulesPersistence::rollbackTransaction();
            throw new InternalErrorException(static::class.":updateScheduleHours", $e->getMessage());
        }


        $trans = SchedulesPersistence::commitTransaction();
        if( !$trans )
            throw new InternalErrorException(static::class.":updateScheduleHours", "Error al registrar transaccion");

    }

    /**
     * @param $scheduleId int
     * @param $status int
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeSchedyleStatus($scheduleId, $status)
    {
        $this->getSchedule_ById( $scheduleId );

        if( $status == Utils::$STATUS_ENABLE ){
            $result = $this->schedulesPer->enableSchedule( $scheduleId );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException(static::class.":chagetScheduleStatus",
                    "Error al habilitar horario", $result->getErrorMessage() );
        }
        else if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->schedulesPer->disableSchedule( $scheduleId );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException(static::class.":chagetScheduleStatus",
                    "Error al deshabilitar horario", $result->getErrorMessage() );
        }
    }


    /**
     * @param $scheduleId int
     * @param $newSubjects array
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function updateScheduleSubjects($scheduleId, $newSubjects)
    {
        //Comprobando si existe horario
        $this->getSchedule_ById( $scheduleId );

        //Se debe agregar las nuevas materias
        // las que ya no estan deben deshabilitarse
        //Si hay asesorias activas asociadas a esa materia, deben finalizarse y notificar a admin y a alumnos
        //---Se obtienen horas y dias
        $subjects = $this->getScheduleSubjects_ById( $scheduleId );

        $trans = SchedulesPersistence::initTransaction();
        if( !$trans )
            throw new InternalErrorException(static::class.":updateScheduleSubjects", "Error al iniciar transaccion");

        //Se comparan cuales ya no estan para deshabilitar
        //NOTA: se le puede poner un try/catch para que continue a pesar del error

        try{
            foreach ( $subjects as $sub ){
                //Si la hora que esta actualmente en el horario, no se encuentra en la update, se deshabilita
                if( !in_array($sub['subject_id'], $newSubjects) ){
                    $this->disableSubjec($sub['id']);
                }
                //si exta ya existe, se habilita
                else{
                    //TODO: se debe tener cuidado si la materia aun no ha sido validada para no habilitarla
                    $this->enableSubject($sub['id']);
                }
            }
        }catch (RequestException $e){
            SchedulesPersistence::rollbackTransaction();
            throw new InternalErrorException(static::class.":updateScheduleSubjects", $e->getMessage());
        }


        //Se registran horas
        try{
            $this->insertScheduleSubjects( $scheduleId, $newSubjects );
        }catch (RequestException $e){
            throw new InternalErrorException(static::class.":updateScheduleSubjects", $e->getMessage());
        }

        //Se registran al insertar
//        $trans = SchedulesPersistence::commitTransaction();
//        if( !$trans )
//            throw new InternalErrorException(static::class.":updateScheduleHours", "Error al registrar transaccion");
    }


    //-----------------HORAS

    /**
     * @param $hourId int
     *
     * @throws InternalErrorException
     */
    private function disableHour($hourId)
    {
        //TODO: notificar a usuarios asociados
        $result = $this->schedulesPer->disableScheduleHour($hourId);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":disableHour",
                "Error al deshabilitar hora de horario: $hourId", $result->getErrorMessage() );

    }

    /**
     * @param $hourId INT
     *
     * @throws InternalErrorException
     */
    private function enableHour($hourId)
    {
        $result = $this->schedulesPer->enableScheduleHour($hourId);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":enableHour",
                "Error al habilitar hora de horario: $hourId", $result->getErrorMessage() );
    }


    //------------------MATERIAS

    /**
     * @param $subId int
     *
     * @throws InternalErrorException
     */
    private function disableSubjec($subId)
    {
        //TODO: notificar a usuarios asociados
        $result = $this->schedulesPer->disableScheduleSubject($subId);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":disableSubject",
                "Error al deshabilitar materia de horario: $subId", $result->getErrorMessage() );

    }

    /**
     * @param $subId INT
     *
     * @throws InternalErrorException
     */
    private function enableSubject($subId)
    {
        $result = $this->schedulesPer->enableScheduleSubject($subId);
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":enableSubject",
                "Error al habilitar materia de horario: $subId", $result->getErrorMessage() );
    }

    /**
     * @param $subject_id int
     * @param $subjects array
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
        foreach ($hours as $s ){
            if( $s['day_hour_id'] == $hour_id )
                return true;
        }
        return false;
    }


}