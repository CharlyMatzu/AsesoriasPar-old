<?php namespace Service;


use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Exceptions\RequestException;
use Persistence\SchedulesPersistence;
use Model\Schedule;
use Utils;

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
            throw new InternalErrorException("Error al obtener horario");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe horario");

        return $result->getData();
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
            throw new InternalErrorException("Error al obtener horario actual de alumno");
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
            throw new InternalErrorException("Error al obtener dias y horas");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $result->getData();
    }

    /**
     * @param $id int
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getScheduleHoursAndDays_ById($id)
    {
        $result = $this->schedulesPer->getScheduleHours_ByScheduleId( $id, SchedulesPersistence::ORDER_BY_DAY );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al obtener dias y horas de horario");
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
            throw new InternalErrorException("Error al obtener materias de horario");
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
        $period = $periodService->getCurrentPeriod();

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
                throw new InternalErrorException("Error al iniciar transaccion");

            //Si no tiene horario, se registra
            $result = $this->schedulesPer->insertSchedule( $studentId, $period['id'] );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("No se pudo registrar horario");


            //Se obtiene horario de alumno en periodo actual
            $current_schedule = null;
            try{
                //se obtiene horario
                $current_schedule = $this->getCurrentSchedule_ByStudentId( $studentId );
            }catch (RequestException $e){
                $trans = SchedulesPersistence::rollbackTransaction();
                throw new RequestException( $e->getMessage(), $e->getStatusCode() );
            }


            try{
                //TODO: comprobar que horas existen
                //Se registran horas
                $this->insertScheduleHours( $current_schedule['id'], $schedule_hours );
            }catch (InternalErrorException $e){
                throw new InternalErrorException( $e->getMessage() );
            }

            //Se guardan los registros
            $trans = SchedulesPersistence::commitTransaction();
            if( !$trans )
                throw new InternalErrorException("Error al registrar transaccion");
            //----------FIN TRANSACCION
        }

    }


    /**
     * @param $scheduleid int
     * @param $hours array
     *
     * @throws InternalErrorException
     */
    public function insertScheduleHours($scheduleid, $hours){
        foreach ( $hours as $hour ){
            $result = $this->schedulesPer->insertScheduleHours( $scheduleid, $hour );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("Error al registrar horas de horario");
        }
    }

    /**
     * @param $scheduleid int
     * @param $subjects array
     *
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function insertScheduleSubjects($scheduleid, $subjects){
        //TODO: verificar que materia no este registrada en horario

        if( !SchedulesPersistence::initTransaction() )
            throw new InternalErrorException("Error al iniciar tranasaccion");

        $subjectService = new SubjectService();
        foreach ( $subjects as $sub ){
            //Comprueba si materia existe
            try{
                $subjectService->getSubject_ById( $sub );
            }catch (RequestException $e){
                SchedulesPersistence::rollbackTransaction();
                throw new RequestException( $e->getMessage(), $e->getStatusCode() );
            }

            $result = $this->schedulesPer->insertScheduleSubjects( $scheduleid, $sub );
            if( Utils::isError( $result->getOperation() ) ) {
                SchedulesPersistence::rollbackTransaction();
                throw new InternalErrorException("Error al registrar materia de horario");
            }
        }

        if( !SchedulesPersistence::commitTransaction() )
            throw new InternalErrorException("Error al registrar tranasaccion");
    }





//    //------------------------ REGISTRO DE HORARIO
//
//    /**
//     * @param $idStudent
//     * @param $hours
//     * @param $subjects
//     * @return array
//     */
//    public function insertStudentSchedule( $idStudent, $hours, $subjects ){
//
//        //Iniciamos transaccion
//        //TODO: Agregar verificacion
//        SchedulesPersistence::initTransaction();
//
//        $result = $this->getCurrentPeriod();
//        if( $result === 'error' ){
//            return Functions::makeArrayResponse(
//                'error',
//                'period',
//                "No se pudo obtener el ciclo actual"
//            );
//        }
//        else if( $result === null ){
//            return Functions::makeArrayResponse(
//                false,
//                'period',
//                "No hay un ciclo actual disponible"
//            );
//        }
//        //Se guarda id del ciclo actual
//        $cycleid = $result['id'];
//        //Verificamos que no tenga un schedule
//        $result = $this->haveStudentCurrSchedule($idStudent);
//        if( $result === 'error' ){
//            return Functions::makeArrayResponse(
//                'error',
//                'schedule',
//                "No se pudo verificar existencia de schedule del student"
//            );
//        }
//        //Si ya tiene un schedule registrado en el ciclo actual
//        else if( $result === true ){
//            return Functions::makeArrayResponse(
//                false,
//                'schedule',
//                "Student ya tiene un schedule registrado"
//            );
//        }
//
//
//        //------------REGISTRO DE HORARIO
//
//        //Verificamos que usuario exista
//        $conStudents = new StudentControl();
//        $result = $conStudents->isStudentExist_ById( $idStudent );
//        if( $result === 'error' ){
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                'error',
//                'student',
//                "No se pudo verificar student"
//            );
//        }
//        else if( $result === null ){
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                false,
//                'student',
//                "Student no existe"
//            );
//        }
//
//
//        //---------HORARIO
//        $result = $this->schedulesPer->insertSchedule( $idStudent, $cycleid );
//        if( !$result ) {
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                'error',
//                'schedule',
//                "Ocurrio un error al registrar schedule"
//            );
//        }
//
//        //Se obtiene schedule (la referencia principal) del student
//        $result = $this->getCurrentScheduleMain_ByStudentId($idStudent);
//        if( $result === 'error' ){
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                false,
//                'schedule',
//                "No se pudo obtener schedule registrado"
//            );
//        }
//        else if( $result === null ){
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                "error",
//                'schedule',
//                "No se encontro schedule registrado del student"
//            );
//        }
//        //Se saca id del schedule
//        $idSchedule = $result['id'];
//
//        //---------HORAS
//        //Se registran horas
//        //TODO: verificar las horas antes de registrar
//        $result = $this->schedulesPer->insertScheduleHours( $idSchedule, $hours );
//        if( !$result ) {
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                'error',
//                'hours',
//                "No se pudieron registrar las horas del schedule"
//            );
//        }
//
//        //---------MATERIAS
//        //TODO: vericicar las materias antes de registrar
//        $result = $this->schedulesPer->insertScheduleSubjects( $idSchedule, $subjects );
//        if( !$result ) {
//            SchedulesPersistence::rollbackTransaction();
//            return Functions::makeArrayResponse(
//                'error',
//                'subjects',
//                "No se pudieron registrar las materias del schedule"
//            );
//        }
//
//        //Si el registro resulto éxitoso
//
//        //Si sale bien
//        SchedulesPersistence::commitTransaction();
//        return Functions::makeArrayResponse(
//            true,
//            "schedule",
//            "Se registro schedule con éxito"
//        );
//    }



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




}