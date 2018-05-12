<?php namespace Persistence;

class Schedules extends Persistence{

    public function __construct(){}

    //------------
    // HORAS / DIAS / PERIODO
    //------------

    /**
     * @return array|bool|null
     */
    public function getDays(){
        $query = "SELECT DISTINCT day FROM day_and_hour ORDER BY day_number";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @return array|bool|null
     */
    public function getHoursAndDays_Ordered(){
        $query = "SELECT 
                        pk_id as 'id',
                        day as 'day',
                        TIME_FORMAT(hour, '%H:%i') as 'hour' 
                        FROM day_and_hour
                      ORDER BY day_number, hour";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @return array|bool|null
     */
    public function getHoursAndDays_OrderByHour(){
        $query = "SELECT 
                        pk_id as 'id',
                        day as 'day',
                        TIME_FORMAT(hour, '%H:%i') as 'hour' 
                        FROM day_and_hour
                      ORDER BY hour, day_number";
        //Obteniendo resultados
        return self::executeQuery($query);
    }



    //------------
    // HORARIO DE ASESOR
    //------------

    /**
     * @param $studentId
     * @param $periodId
     * @return array|bool|null
     */
    public function getScheduleMain_ByStudentId($studentId, $periodId){
        $query = "SELECT 
                        pk_id as 'id',
                        register_date as 'register_date',
                        validated as 'validated',
                        status as 'status'
                      FROM schedule h
                      WHERE h.fk_period = ".$periodId." AND h.fk_student = ".$studentId."
                      ORDER BY h.pk_id DESC LIMIT 1";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param int $scheduleid
     * @return array|bool|null
     */
    public function getScheduleHours_ByScheduleId(int $scheduleid ){
        $query = "SELECT 
                            dh.pk_id as 'id',
                            dh.hour as 'hour',
                            dh.day as 'day'
                        FROM schedule_days_hours hdh
                        INNER JOIN day_and_hour dh ON dh.pk_id = hdh.fk_day_and_hour
                        WHERE hdh.fk_schedule = ".$scheduleid."
                        ORDER BY dh.pk_id";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $studentID
     * @param $periodId
     * @return array|bool|null
     */
    public function insertSchedule($studentID, $periodId){
        $query = "INSERT INTO schedule(fk_student, fk_period)
                      VALUES (".$studentID.", ".$periodId.")";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $scheduleId
     * @param $hourArray
     * @return bool
     */
    public function insertScheduleHours($scheduleId, $hourArray){
        foreach($hourArray as $hour ){
            $query = "INSERT INTO schedule_days_hours (fk_schedule, fk_day_and_hour) VALUES
                      (".$scheduleId.", ".$hour.")";
            $result = self::executeQuery($query);
            //Si ocurrio un error, Pelos!
            if( !$result )
                return false;
        }
        //Si salio bien
        return true;
    }

    /**
     * @param $scheduleId Id correspondiente al estudiante
     * @param $subjectArray Lista de materias
     * @return bool
     */
    public function insertScheduleSubjects($scheduleId , $subjectArray){
        foreach($subjectArray as $sub ){
            $query = "INSERT INTO schedule_subject (fk_schedule, fk_subject) VALUES
                        (".$scheduleId.", ".$sub.");";
            $result = self::executeQuery($query);
            //Si ocurrio un error, Pelos!
            if( !$result )
                return false;
        }
        //Si salio bien
        return true;
    }


    /**
     * Obtiene los horarios (sin repetir) de los asesores que dan advisory de una materia
     * en especifico
     * @param $subjectId string id de materia a buscar
     * @param $studentId string estudiante a omitir
     * @param $periodId string ciclo del schedule en el que se buscará
     * @return array|bool|null
     */
    public function getAvailSchedules_SkipStudent_ByPeriod($subjectId, $studentId, $periodId){
        $query = "SELECT DISTINCT
                        dh.pk_id as 'id',
                        dh.hour as 'hour',
                        dh.day as 'day'
                    FROM schedule_subjects hm
                    INNER JOIN schedule h ON h.pk_id = hm.fk_schedule
                    INNER JOIN schedule_days_hours hdh ON hdh.fk_schedule = h.pk_id
                    INNER JOIN day_and_hour dh ON dh.pk_id = hdh.fk_day_and_hour
                    INNER JOIN student e ON e.pk_id = h.fk_student
                    INNER JOIN subject m ON m.pk_id = hm.fk_subject
                    WHERE (h.fk_period = ".$periodId.") AND (e.pk_id <> ".$studentId.") AND (m.pk_id = ".$subjectId.")
                    ORDER BY dh.hour, dh.pk_id";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /*
    public function getAvailSchedules($subjectId, $studentId, $cycleId){
        $query = "SELECT DISTINCT
                    dh.PK_id as 'id',
                    dh.hora as 'hora',
                    dh.dia as 'dia'
                FROM horario_materia hm
                INNER JOIN schedule h ON h.PK_id = hm.FK_horario
                INNER JOIN horario_dia_hora hdh ON hdh.FK_horario = h.PK_id
                INNER JOIN dia_hora dh ON dh.PK_id = hdh.FK_dia_hora
                INNER JOIN estudiante e ON e.PK_id = h.FK_estudiante
                INNER JOIN materia m ON m.PK_id = hm.FK_materia
                ORDER BY dh.hora, dh.PK_id";
        //Obteniendo resultados
        return self::executeQuery($query);
    }
    */




}
