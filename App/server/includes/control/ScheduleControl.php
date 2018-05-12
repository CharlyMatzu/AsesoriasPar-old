<?php namespace Control;


use Persistence\Schedules;
use Objects\Schedule;

class ScheduleControl{

    private $perHorarios;

    public function __construct(){
        $this->perHorarios = new Schedules();
    }

    public function getDays(){
        $result = $this->perHorarios->getDays();
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


    public function getHoursAndDays(){
        $result = $this->perHorarios->getHoursAndDays_Ordered();
        if( !is_array($result) )
            return $result;
        else{
            $hourAndDays = array();
            foreach( $result as $hd )
                $hourAndDays[] = $this->makeArray_HoursAndDays($hd);
            return $hourAndDays;
        }
    }


    public function getHoursAndDays_OrderByHour(){
        $result = $this->perHorarios->getHoursAndDays_OrderByHour();
        if( !is_array($result) )
            return $result;
        else{
            $hourAndDays = array();
            foreach( $result as $hd )
                $hourAndDays[] = $this->makeArray_HoursAndDays($hd);
            return $hourAndDays;
        }
    }

    /**
     * @return array|null|string
     */
    public function getCurrentPeriod(){
        $result = $this->perHorarios->getCurrentPeriod();
        if( $result === false )
            return 'error';
        else if( $result == null )
            return null;
        else{
            //Si array esta vacio
            if( count($result) == 0 )
                return null;
            //Si tiene datos
            else {
                $cycle = [
                    "id" => $result[0]['id'],
                    'start' => $result[0]['start'],
                    'end' => $result[0]['end']
                ];
                return $cycle;
            }
        }
    }



    //------------------------
    //  HORARIO DEL ESTUDIANTE
    //------------------------


    /**
     * Obtiene los datos completos del schedule de un student
     * @param String|int $id
     * @return array|bool|schedule|null|string
     */
    public function getFullCurrentSchedule_ByStudentId( $id ){
        $result = $this->getCurrentScheduleMain_ByStudentId( $id );
        if( $result === false )
            return 'error';
        else if( $result === null )
            return null;
        else{

            $schedule = $result;
            //Se obtienen materias
            $subjects = $this->getScheduleSubject_ByScheduleId( $schedule['id'] );
            //Se obtienen horas y dias
            $hoursAndDays = $this->getScheduleHours_ByScheduleId( $schedule['id'] );

            //----Creando objeto
            $scheduleObj = new Schedule();
            //TODO: verificar validacion de schedule
            $scheduleObj->setId( $schedule['id'] );
            $scheduleObj->setStatus( $schedule['status'] );
            $scheduleObj->setPeriod( $hoursAndDays );
            return $scheduleObj;
        }
    }

    /**
     * Obtiene la referencia general del schedule del student
     * @param int $id String|int
     * @return array|bool|null
     */
    public function getCurrentScheduleMain_ByStudentId($id){
        $cycle = $this->getCurrentPeriod();
        //Si no es el resultado esperado
        if( !is_array($cycle) )
            return $cycle;
        else{
            //Si existe ciclo se busca schedule del student
            $result = $this->perHorarios->getScheduleMain_ByStudentId( $id, $cycle['id'] );
            if( $result === false )
                return 'error';
            else if( $result === null )
                return null;
            else
                return $this->makeArray_Schedule( $result[0] );
        }
    }


    /**
     * Obtenemos Subjects de schedule especifico
     * @param int $scheduleid
     * @return array|bool|string
     */
    public function getScheduleSubject_ByScheduleId( $scheduleid ){
        $conMaterias = new SubjectControl();
        return $conMaterias->getScheduleSubjects_ByScheduleId( $scheduleid );
    }

    /**
     * Obtenemos Horas de un schedule especifico
     * @param String|int $idSchedule
     * @return array|bool|string
     */
    public function getScheduleHours_ByScheduleId($idSchedule ){
        $result = $this->perHorarios->getScheduleHours_ByScheduleId( $idSchedule );
        if( $result === false )
            return 'error';
        else if( $result === false )
            return null;
        else{
            $arrayHoras = array();
            foreach( $result as $hd ){
                $arrayHoras[] = $this->makeArray_HoursAndDays( $hd );
            }
            return $arrayHoras;
        }
    }


    /**
     * @param $idStudent String|int del student
     * @return bool|string
     */
    public function haveStudentCurrSchedule($idStudent){
        $result = $this->getCurrentScheduleMain_ByStudentId($idStudent);
        if( $result === false )
            return 'error';
        else if( $result === null)
            return false;
        else
            return true;
    }

    /**
     * Comprueba que un schedule exista mediante su ID
     * @param int $scheduleId id del schedule a verificar
     * @return bool|string
     * Regresa FALSE cuando no existe
     * TRUE cuando existe
     * regresa la cadena 'error' cuando Ocurrio un error
     */
    public function isScheduleExist( $scheduleId ){
        $result = $this->getCurrentScheduleMain_ByStudentId( $scheduleId );
        //Error
        if( $result == false ){
            return 'error';
        }
        //No existe
        else if( $result != null )
            return true;
        //Existe
        else
            return false;
    }



    //------------------------ REGISTRO DE HORARIO

    /**
     * @param $idStudent
     * @param $hours
     * @param $subjects
     * @return array
     */
    public function insertStudentSchedule( $idStudent, $hours, $subjects ){

        //Iniciamos transaccion
        //TODO: Agregar verificacion
        Schedules::initTransaction();

        $result = $this->getCurrentPeriod();
        if( $result === 'error' ){
            return Functions::makeArrayResponse(
                'error',
                'period',
                "No se pudo obtener el ciclo actual"
            );
        }
        else if( $result === null ){
            return Functions::makeArrayResponse(
                false,
                'period',
                "No hay un ciclo actual disponible"
            );
        }
        //Se guarda id del ciclo actual
        $cycleid = $result['id'];
        //Verificamos que no tenga un schedule
        $result = $this->haveStudentCurrSchedule($idStudent);
        if( $result === 'error' ){
            return Functions::makeArrayResponse(
                'error',
                'schedule',
                "No se pudo verificar existencia de schedule del student"
            );
        }
        //Si ya tiene un schedule registrado en el ciclo actual
        else if( $result === true ){
            return Functions::makeArrayResponse(
                false,
                'schedule',
                "Student ya tiene un schedule registrado"
            );
        }


        //------------REGISTRO DE HORARIO

        //Verificamos que usuario exista
        $conStudents = new StudentControl();
        $result = $conStudents->isStudentExist_ById( $idStudent );
        if( $result === 'error' ){
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                'error',
                'student',
                "No se pudo verificar student"
            );
        }
        else if( $result === null ){
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                false,
                'student',
                "Student no existe"
            );
        }


        //---------HORARIO
        $result = $this->perHorarios->insertSchedule( $idStudent, $cycleid );
        if( !$result ) {
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                'error',
                'schedule',
                "Ocurrio un error al registrar schedule"
            );
        }

        //Se obtiene schedule (la referencia principal) del student
        $result = $this->getCurrentScheduleMain_ByStudentId($idStudent);
        if( $result === 'error' ){
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                false,
                'schedule',
                "No se pudo obtener schedule registrado"
            );
        }
        else if( $result === null ){
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                "error",
                'schedule',
                "No se encontro schedule registrado del student"
            );
        }
        //Se saca id del schedule
        $idSchedule = $result['id'];

        //---------HORAS
        //Se registran horas
        //TODO: verificar las horas antes de registrar
        $result = $this->perHorarios->insertScheduleHours( $idSchedule, $hours );
        if( !$result ) {
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                'error',
                'hours',
                "No se pudieron registrar las horas del schedule"
            );
        }

        //---------MATERIAS
        //TODO: vericicar las materias antes de registrar
        $result = $this->perHorarios->insertScheduleSubjects( $idSchedule, $subjects );
        if( !$result ) {
            Schedules::rollbackTransaction();
            return Functions::makeArrayResponse(
                'error',
                'subjects',
                "No se pudieron registrar las materias del schedule"
            );
        }

        //Si el registro resulto éxitoso

        //Si sale bien
        Schedules::commitTransaction();
        return Functions::makeArrayResponse(
            true,
            "schedule",
            "Se registro schedule con éxito"
        );
    }



    //----------------------
    // ASESORIAS
    //----------------------

    public function getCurrAvailSchedules_SkipStudent($subjectId, $studentId){
        $cycle = $this->getCurrentPeriod();
        if( !is_array($cycle) )
            return $cycle;
        else{
            $result = $this->perHorarios->getAvailSchedules_SkipStudent_ByPeriod( $subjectId, $studentId, $cycle['id'] );
            if( $result == false )
                return 'error';
            else
                return $result;
        }

    }




    //------------------------------
    // FUNCIONES ADICIONALES
    //------------------------------

    private static function makeArray_Schedule($s){
        $hoursAndDays = [
            'id'            => $s['id'],
            'date'          => $s['register_date'],
            'validation'    => $s['validated'],
            'status'        => $s['status']
        ];
        return $hoursAndDays;
    }



    private static function makeArray_HoursAndDays($hd){
        $hoursAndDays = [
            'id'  => $hd['id'],
            'day'  => $hd['day'],
            'hour' => $hd['hour']
        ];
        return $hoursAndDays;
    }




}