<?php namespace Service;


use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
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
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getSchedule_ByStudentId($studentId)
    {
        //--------Comprobando si existe alumno
        $result = $this->schedulesPer->getSchedule_ByStudentId($studentId);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al obtener horario de alumno");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe horario de alumno");

        return $result->getData();
    }


//    /**
//     * @param $scheduleId
//     *
//     * @return mixed
//     * @throws InternalErrorException
//     * @throws NotFoundException
//     */
//    public function getScheduleHours_ById($scheduleId){
//        $result = $this->schedulesPer->getSchedule();
//
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException("Error al obtener horario de alumno");
//        else if( Utils::isEmpty( $result->getOperation() ) )
//            throw new NotFoundException("No existe horario de alumno");
//
//        return $result->getData();
//    }

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





//    public function getHoursAndDays_OrderByHour(){
//        $result = $this->schedulesPer->getHoursAndDays_OrderByHour();
//        if( !is_array($result) )
//            return $result;
//        else{
//            $hourAndDays = array();
//            foreach( $result as $hd )
//                $hourAndDays[] = $this->makeArray_HoursAndDays($hd);
//            return $hourAndDays;
//        }
//    }
//
//    /**
//     * @return array|null|string
//     */
//    public function getCurrentPeriod(){
//        $result = $this->schedulesPer->getCurrentPeriod();
//        if( $result === false )
//            return 'error';
//        else if( $result == null )
//            return null;
//        else{
//            //Si array esta vacio
//            if( count($result) == 0 )
//                return null;
//            //Si tiene datos
//            else {
//                $cycle = [
//                    "id" => $result[0]['id'],
//                    'start' => $result[0]['start'],
//                    'end' => $result[0]['end']
//                ];
//                return $cycle;
//            }
//        }
//    }
//
//
//
//    //------------------------
//    //  HORARIO DEL ESTUDIANTE
//    //------------------------
//
//
//    /**
//     * Obtiene los datos completos del schedule de un student
//     * @param String|int $id
//     * @return array|bool|schedule|null|string
//     */
//    public function getFullCurrentSchedule_ByStudentId( $id ){
//        $result = $this->getCurrentScheduleMain_ByStudentId( $id );
//        if( $result === false )
//            return 'error';
//        else if( $result === null )
//            return null;
//        else{
//
//            $schedule = $result;
//            //Se obtienen materias
//            $subjects = $this->getScheduleSubject_ByScheduleId( $schedule['id'] );
//            //Se obtienen horas y dias
//            $hoursAndDays = $this->getScheduleHours_ByScheduleId( $schedule['id'] );
//
//            //----Creando objeto
//            $scheduleObj = new Schedule();
//            //TODO: verificar validacion de schedule
//            $scheduleObj->setId( $schedule['id'] );
//            $scheduleObj->setStatus( $schedule['status'] );
//            $scheduleObj->setPeriod( $hoursAndDays );
//            return $scheduleObj;
//        }
//    }
//
//    /**
//     * Obtiene la referencia general del schedule del student
//     * @param int $id String|int
//     * @return array|bool|null
//     */
//    public function getCurrentScheduleMain_ByStudentId($id){
//        $cycle = $this->getCurrentPeriod();
//        //Si no es el resultado esperado
//        if( !is_array($cycle) )
//            return $cycle;
//        else{
//            //Si existe ciclo se busca schedule del student
//            $result = $this->schedulesPer->getScheduleMain_ByStudentId( $id, $cycle['id'] );
//            if( $result === false )
//                return 'error';
//            else if( $result === null )
//                return null;
//            else
//                return $this->makeArray_Schedule( $result[0] );
//        }
//    }
//
//
//    /**
//     * Obtenemos SubjectsPersistence de schedule especifico
//     * @param int $scheduleid
//     * @return array|bool|string
//     */
//    public function getScheduleSubject_ByScheduleId( $scheduleid ){
//        $conMaterias = new SubjectControl();
//        return $conMaterias->getScheduleSubjects_ByScheduleId( $scheduleid );
//    }
//
//    /**
//     * Obtenemos Horas de un schedule especifico
//     * @param String|int $idSchedule
//     * @return array|bool|string
//     */
//    public function getScheduleHours_ByScheduleId($idSchedule ){
//        $result = $this->schedulesPer->getScheduleHours_ByScheduleId( $idSchedule );
//        if( $result === false )
//            return 'error';
//        else if( $result === false )
//            return null;
//        else{
//            $arrayHoras = array();
//            foreach( $result as $hd ){
//                $arrayHoras[] = $this->makeArray_HoursAndDays( $hd );
//            }
//            return $arrayHoras;
//        }
//    }
//
//
//    /**
//     * @param $idStudent String|int del student
//     * @return bool|string
//     */
//    public function haveStudentCurrSchedule($idStudent){
//        $result = $this->getCurrentScheduleMain_ByStudentId($idStudent);
//        if( $result === false )
//            return 'error';
//        else if( $result === null)
//            return false;
//        else
//            return true;
//    }
//
//    /**
//     * Comprueba que un schedule exista mediante su ID
//     * @param int $scheduleId id del schedule a verificar
//     * @return bool|string
//     * Regresa FALSE cuando no existe
//     * TRUE cuando existe
//     * regresa la cadena 'error' cuando Ocurrio un error
//     */
//    public function isScheduleExist( $scheduleId ){
//        $result = $this->getCurrentScheduleMain_ByStudentId( $scheduleId );
//        //Error
//        if( $result == false ){
//            return 'error';
//        }
//        //No existe
//        else if( $result != null )
//            return true;
//        //Existe
//        else
//            return false;
//    }
//
//
//
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
//
//
//
//    //----------------------
//    // ASESORIAS
//    //----------------------
//
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
//
//
//
//
//    //------------------------------
//    // FUNCIONES ADICIONALES
//    //------------------------------
//

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