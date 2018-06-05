<?php namespace App\Persistence;

use App\Model\Schedule;
use App\Utils;

class SchedulesPersistence extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT
                            s.schedule_id as 'id',
                            s.status as 'status',
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
     * @return \App\Model\DataResult
     */
    public function getSchedule_Byid($id){
        $query = $this->SELECT."
                WHERE  s.schedule_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $studentId int
     * @param $period int
     * @return \App\Model\DataResult
     */
    public function getSchedule_ByStudentId_Period($studentId, $period){
        $query = $this->SELECT."
                WHERE  s.fk_student = $studentId AND s.fk_period = $period";
        return  self::executeQuery($query);
    }


    /**
     * @return \App\Model\DataResult
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
     * @return \App\Model\DataResult
     */
    public function getScheduleHours_ByScheduleId( $scheduleid, $orderType ){
        $query = "SELECT
                        sdh.schedule_dh_id as 'id',
                        sdh.fk_day_hour as 'day_hour_id',
                        dh.day as 'day',
                        TIME_FORMAT(dh.hour, '%H:%i') as 'hour'
                    FROM schedule_days_hours sdh
                    INNER JOIN day_and_hour dh ON sdh.fk_day_hour = dh.day_hour_id
                    WHERE sdh.fk_schedule = $scheduleid
                    ORDER BY $orderType";


        return self::executeQuery($query);
    }

    /**
     * @param int $scheduleid
     * @param String $orderType
     *
     * @see SchedulesPersistence::ORDER_BY_DAY
     * @see SchedulesPersistence::ORDER_BY_HOUR
     * @return \App\Model\DataResult
     */
    public function getScheduleHours_ByScheduleId_Enabled( $scheduleid, $orderType ){
        $query = "SELECT
                        sdh.schedule_dh_id as 'id',
                        sdh.fk_day_hour as 'day_hour_id',
                        dh.day as 'day',
                        TIME_FORMAT(dh.hour, '%H:%i') as 'hour'
                    FROM schedule_days_hours sdh
                    INNER JOIN day_and_hour dh ON sdh.fk_day_hour = dh.day_hour_id
                    WHERE sdh.fk_schedule = $scheduleid AND sdh.status = ".Utils::$STATUS_ENABLE."
                    ORDER BY $orderType";


        return self::executeQuery($query);
    }

    /**
     * @param int $scheduleid
     * @return \App\Model\DataResult
     * TODO: solo materias habilitadas
     */
    public function getScheduleSubjects_ById($scheduleid)
    {
        $query = "SELECT
                  ss.schedule_subject_id as 'id',
                  s.subject_id as 'subject_id',
                  s.name as 'subject_name',
                  ss.status as 'status'
                  
                FROM schedule_subjects ss
                INNER JOIN subject s ON ss.fk_subject = s.subject_id
                WHERE ss.fk_schedule = $scheduleid AND 
                s.status = ".Utils::$STATUS_ENABLE;

        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param int $scheduleid
     * @return \App\Model\DataResult
     * TODO: solo materias habilitadas
     */
    public function getScheduleSubjects_ById_Enabled($scheduleid)
    {
        $query = "SELECT
                  ss.schedule_subject_id as 'id',
                  s.subject_id as 'subject_id',
                  s.name as 'subject_name',
                  ss.status as 'status'
                  
                FROM schedule_subjects ss
                INNER JOIN subject s ON ss.fk_subject = s.subject_id
                WHERE ss.fk_schedule = $scheduleid AND 
                (s.status = ".Utils::$STATUS_ENABLE." AND ss.status = ".Utils::$STATUS_ENABLE.")";

        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $studentId int
     * @param $periodId int
     *
     * @return \App\Model\DataResult
     */
    public function insertSchedule($studentId, $periodId ){
        $query = "INSERT INTO schedule(fk_student, fk_period) 
                  VALUES($studentId, $periodId)";

        return  self::executeQuery($query);
    }


    /**
     * @param $scheduleId
     * @param $hour
     * @return \App\Model\DataResult
     */
    //TODO: corregir
    public function insertScheduleHours($scheduleId, $hour){
        $query = "INSERT INTO schedule_days_hours (fk_schedule, fk_day_hour) 
                  VALUES ($scheduleId, $hour)";

        return self::executeQuery($query);
    }

    /**
     * @param $scheduleId int correspondiente al estudiante
     * @param $subject array de materias
     *
     * @return \App\Model\DataResult
     * TODO: status debe ser 1 para confirmar por admin
     */
    public function insertScheduleSubjects($scheduleId, $subject){
        $query = "INSERT INTO schedule_subjects (fk_schedule, fk_subject, status) 
                      VALUES ($scheduleId, $subject, ".Utils::$STATUS_ACTIVE.")";

        return self::executeQuery($query);
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
     * @return \App\Model\DataResult
     */
    public function getDaysAndHours($orderType ){
        $query = "SELECT 
                        dh.day_hour_id as 'id',
                        day as 'day',
                        TIME_FORMAT(hour, '%H:%i') as 'hour',
                        day_number as 'day_number'
                        FROM day_and_hour dh
                      ORDER BY $orderType";
        //Obteniendo resultados
        return self::executeQuery($query);
    }


    /**
     * Obtiene solo los dias registrados sin repetir
     * @return \App\Model\DataResult
     */
    public function getDays(){
        $query = "SELECT DISTINCT day, day_number 
                  FROM day_and_hour 
                  ORDER BY day_number";
        //Obteniendo resultados
        return self::executeQuery($query);
    }


    /**
     * @param $scheduleId int
     *
     * @return \App\Model\DataResult
     */
    public function disableSchedule($scheduleId)
    {
        $query = "UPDATE schedule
                  SET status = ".Utils::$STATUS_DISABLE."
                  WHERE schedule_id = $scheduleId";
        return  self::executeQuery($query);
    }

    /**
     * @param $scheduleId int
     *
     * @return \App\Model\DataResult
     */
    public function enableSchedule($scheduleId)
    {
        $query = "UPDATE schedule
                  SET status = ".Utils::$STATUS_ENABLE."
                  WHERE schedule_id = $scheduleId";
        return  self::executeQuery($query);
    }


    /**
     * @param $hdId int
     *
     * @return \App\Model\DataResult
     */
    public function disableScheduleHour($hdId)
    {
        $query = "UPDATE schedule_days_hours
                  SET status = ".Utils::$STATUS_DISABLE."
                  WHERE schedule_dh_id = $hdId";
        return  self::executeQuery($query);
    }

    /**
     * @param $hdId int
     *
     * @return \App\Model\DataResult
     */
    public function enableScheduleHour($hdId)
    {
        $query = "UPDATE schedule_days_hours
                  SET status = ".Utils::$STATUS_ENABLE."
                  WHERE schedule_dh_id = $hdId";
        return  self::executeQuery($query);
    }


    /**
     * @param $subjectId
     *
     * @return \App\Model\DataResult
     */
    public function disableScheduleSubject($subjectId)
    {
        $query = "UPDATE schedule_subjects
                  SET status = ".Utils::$STATUS_DISABLE."
                  WHERE schedule_subject_id = $subjectId";
        return  self::executeQuery($query);
    }

    /**
     * @param $subjectId int
     *
     * @return \App\Model\DataResult
     */
    public function enableScheduleSubject($subjectId)
    {
        $query = "UPDATE schedule_subjects
                  SET status = ".Utils::$STATUS_ENABLE."
                  WHERE schedule_subject_id = $subjectId";
        return  self::executeQuery($query);
    }

    /**
     * @param $subject_id int
     * @param $period_id int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisers_BySubject_ByPeriod($subject_id, $period_id)
    {
        $query = "SELECT
                    s2.student_id as 'student_id',
                    u.email,
                    s2.first_name,
                    s2.last_name,
                    s2.phone,
                    s2.itson_id,
                    s2.avatar as 'avatar'
                  FROM schedule s
                  INNER JOIN student s2 ON s.fk_student = s2.student_id
                  INNER JOIN user u ON s2.fk_user = u.user_id
                  INNER JOIN schedule_subjects ss ON s.schedule_id = ss.fk_schedule
                  WHERE ss.fk_subject = $subject_id AND
                        ss.status = ".Utils::$STATUS_ACTIVE." AND
                        u.status = ".Utils::$STATUS_ACTIVE." AND
                        s.fk_period = $period_id";
        return  self::executeQuery($query);
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
