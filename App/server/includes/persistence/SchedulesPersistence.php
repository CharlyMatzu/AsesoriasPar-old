<?php namespace Persistence;

use Model\Schedule;

class SchedulesPersistence extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT
                            s.schedule_id as 'id',
                            s.date_register 'register_date',
                            s.fk_period as 'period_id',
                            s.fk_student as 'student_id'
                            FROM schedule s ";

    //--------------------
    //  HORARIO
    //--------------------

    /**
     * @param $id int
     *
     * @return \Model\DataResult
     */
    public function getSchedule_Byid($id){
        $query = $this->SELECT."
                WHERE  s.schedule_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $studentId int
     * @param $period int
     * @return \Model\DataResult
     */
    public function getSchedule_ByStudentId_Period($studentId, $period){
        $query = $this->SELECT."
                WHERE  s.fk_student = $studentId AND s.fk_period = $period";
        return  self::executeQuery($query);
    }


    /**
     * @return \Model\DataResult
     */
    public function getSchedule_Last(){
        $query = $this->SELECT." 
                  ORDER BY s.schedule_id DESC LIMIT 1";
        return  self::executeQuery($query);
    }



    const ORDER_BY_HOUR = "dh.hour, dh.day_number";
    const ORDER_BY_DAY = "dh.day_number, dh.hour";

    /**
     * @param int $scheduleid
     * @param String $orderType
     *
     * @see SchedulesPersistence::ORDER_BY_DAY
     * @see SchedulesPersistence::ORDER_BY_HOUR
     * @return \Model\DataResult
     */
    public function getScheduleHours_ByScheduleId( $scheduleid, $orderType ){
        $query = "SELECT
                        sdh.schedule_dh_id as 'id',
                        dh.day as 'day',
                        TIME_FORMAT(dh.hour, '%H:%i') as 'hour'
                    FROM schedule_days_hours sdh
                    INNER JOIN day_and_hour dh ON sdh.fk_day_hour = dh.day_hour_id
                    WHERE sdh.fk_schedule = $scheduleid
                    ORDER BY $orderType";

        //TODO: cambiar orden en caso de requerir
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param int $scheduleid
     * @return \Model\DataResult
     */
    public function getScheduleSubjects_ById($scheduleid)
    {
        $query = "SELECT
                  ss.schedule_subject_id as 'id',
                  s.name as 'subject',
                  s.status as 'status'
                FROM schedule_subjects ss
                INNER JOIN subject s ON ss.fk_subject = s.subject_id
                WHERE ss.fk_schedule = $scheduleid";

        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $studentId int
     * @param $periodId int
     *
     * @return \Model\DataResult
     */
    public function insertSchedule($studentId, $periodId ){
        $query = "INSERT INTO schedule(fk_student, fk_period) 
                  VALUES($studentId, $periodId)";

        return  self::executeQuery($query);
    }


    /**
     * @param $scheduleId
     * @param $hourArray
     * @return bool
     */
    //TODO: corregir
    public function insertScheduleHours($scheduleId, $hourArray){
        foreach($hourArray as $hour ){
            $query = "INSERT INTO schedule_days_hours (fk_schedule, fk_day_hour) VALUES
                      ($scheduleId, $hour)";
            $result = self::executeQuery($query);
            //Si ocurrio un error, Pelos!
            if( !$result )
                return false;
        }
        //Si salio bien
        return true;
    }

    /**
     * @param $scheduleId int correspondiente al estudiante
     * @param $subjectArray array de materias
     * @return bool
     * TODO: CAMBIAR FORMA DE REGISTRO, HACERLO DESDE EL SERVICE Y USAR TRANSACCIONES
     */
    public function insertScheduleSubjects($scheduleId , $subjectArray){
        foreach($subjectArray as $sub ){
            $query = "INSERT INTO schedule_subjects (fk_schedule, fk_subject) 
                      VALUES ($scheduleId, $sub)";

            $result = self::executeQuery($query);
            //Si ocurrio un error, Pelos!
            if( !$result )
                return false;
        }
        //Si salio bien
        return true;
    }

    //------------
    // HORAS / DIAS
    //------------

    /**
     * Retorna todos los dias y horas disponibles en orden de hora y luego de dia
     *
     * @param String $orderType
     *
     * @see SchedulesPersistence::ORDER_BY_DAY
     * @see SchedulesPersistence::ORDER_BY_HOUR
     * @return \Model\DataResult
     */
    public function getHoursAndDays( $orderType ){
        $query = "SELECT 
                        dh.day_hour_id as 'id',
                        day as 'day',
                        TIME_FORMAT(hour, '%H:%i') as 'hour'
                        FROM day_and_hour dh
                      ORDER BY $orderType";
        //Obteniendo resultados
        return self::executeQuery($query);
    }


    /**
     * Obtiene solo los dias registrados sin repetir
     * @return \Model\DataResult
     */
    public function getDays(){
        $query = "SELECT DISTINCT day 
                  FROM day_and_hour 
                  ORDER BY day_number";
        //Obteniendo resultados
        return self::executeQuery($query);
    }









//    /**
//     * Obtiene los horarios (sin repetir) de los asesores que dan advisory de una materia
//     * en especifico
//     * @param $subjectId string id de materia a buscar
//     * @param $studentId string estudiante a omitir
//     * @param $periodId string ciclo del schedule en el que se buscará
//     * @return array|bool|null
//     */
//    public function getAvailSchedules_SkipStudent_ByPeriod($subjectId, $studentId, $periodId){
//        $query = "SELECT DISTINCT
//                        dh.pk_id as 'id',
//                        dh.hour as 'hour',
//                        dh.day as 'day'
//                    FROM schedule_subjects hm
//                    INNER JOIN schedule h ON h.pk_id = hm.fk_schedule
//                    INNER JOIN schedule_days_hours hdh ON hdh.fk_schedule = h.pk_id
//                    INNER JOIN day_and_hour dh ON dh.pk_id = hdh.fk_day_and_hour
//                    INNER JOIN student e ON e.pk_id = h.fk_student
//                    INNER JOIN subject m ON m.pk_id = hm.fk_subject
//                    WHERE (h.fk_period = ".$periodId.") AND (e.pk_id <> ".$studentId.") AND (m.pk_id = ".$subjectId.")
//                    ORDER BY dh.hour, dh.pk_id";
//        //Obteniendo resultados
//        return self::executeQuery($query);
//    }
//
//
//
//    /*
//    public function getAvailSchedules($subjectId, $studentId, $cycleId){
//        $query = "SELECT DISTINCT
//                    dh.PK_id as 'id',
//                    dh.hora as 'hora',
//                    dh.dia as 'dia'
//                FROM horario_materia hm
//                INNER JOIN schedule h ON h.PK_id = hm.FK_horario
//                INNER JOIN horario_dia_hora hdh ON hdh.FK_horario = h.PK_id
//                INNER JOIN dia_hora dh ON dh.PK_id = hdh.FK_dia_hora
//                INNER JOIN estudiante e ON e.PK_id = h.FK_estudiante
//                INNER JOIN materia m ON m.PK_id = hm.FK_materia
//                ORDER BY dh.hora, dh.PK_id";
//        //Obteniendo resultados
//        return self::executeQuery($query);
//    }
//    */




}
