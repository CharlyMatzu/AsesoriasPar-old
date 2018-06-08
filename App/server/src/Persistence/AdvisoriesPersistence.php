<?php namespace App\Persistence;


use App\Model\AdvisoryModel;
use App\Utils;

class AdvisoriesPersistence extends Persistence{

    public function __construct(){}

//s_alum.student_id as 'student_id',
//s_advi.student_id as 'adviser_id',
//TODO: obtener datos de estudiantes
    private $SELECT = "SELECT
                      ar.advisory_id as 'id',
                      ar.date_start as 'date_start',
                      ar.date_end as 'date_end',
                      ar.date_register as 'date_register',
                      ar.status as 'status',
                      ar.fk_period as 'period_id',

                      s_alum.student_id as 'alumn_id',
                      s_alum.first_name as 'alumn_first_name',
                      s_alum.last_name as 'alumn_last_name',
                      CONCAT('assets/images/', s_alum.avatar) as 'alumn_avatar',
                      
                      s_advi.student_id as 'adviser_id',
                      s_advi.first_name as 'adviser_first_name',
                      s_advi.last_name as 'adviser_last_name',
                      CONCAT('assets/images/', s_advi.avatar) as 'adviser_avatar',
                      
                      s.subject_id as 'subject_id',
                      s.name as 'subject_name'
                  FROM advisory_request ar
                  INNER JOIN student s_alum ON s_alum.student_id = ar.fk_student
                  INNER JOIN subject s ON s.subject_id = ar.fk_subject
                  LEFT JOIN student s_advi ON s_advi.student_id = ar.fk_adviser
                  LEFT JOIN schedule_subjects hm ON hm.schedule_subject_id = ar.fk_subject
                  LEFT JOIN schedule h ON h.schedule_id = hm.fk_schedule ";


    /**
     * @return \App\Model\DataResult
     */
    public function getAdvisories(){
        $query = $this->SELECT;
        return self::executeQuery($query);
    }

    /**
     * @param $periodId int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisories_ByPeriod($periodId){
        $query = $this->SELECT.
            "WHERE ar.fk_period = $periodId";
        return self::executeQuery($query);
    }

    /**
     * @param $id int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisory_ById($id){
        $query = $this->SELECT.
            "WHERE ar.advisory_id = $id";
        return self::executeQuery($query);
    }

    /**
     * @param $student_id int
     * @param $period_id int
     *
     * @return \App\Model\DataResult
     */
    public function getRequestedAdvisories_ByStuden_ByPeriod($student_id, $period_id)
    {
        $query = $this->SELECT.
            "WHERE s_alum.student_id = $student_id AND ar.fk_period = $period_id";
        return self::executeQuery($query);
    }

    /**
     * @param $student_id int
     * @param $period_id int
     *
     * @return \App\Model\DataResult
     */
    public function getAdviserAdvisories_ByStuden_ByPeriod($student_id, $period_id)
    {
        $query = $this->SELECT.
            "WHERE s_advi.student_id = $student_id AND ar.fk_period = $period_id";
        return self::executeQuery($query);
    }


    /**
     * @param $advisoryId int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisoryHours_ById($advisoryId){
        $query = "SELECT 
                    ads.advisory_schedule_id as 'id',
                    ads.fk_hours as 'schedule_hour',
                    h2.day_hour_id as 'day_hour_id',
                    h2.day as 'day',
                    h2.hour as 'hour'
                    FROM advisory_schedule ads
                    INNER JOIN schedule_days_hours h ON ads.fk_hours = h.schedule_dh_id
                    INNER JOIN day_and_hour h2 ON h.fk_day_hour = h2.day_hour_id
                    WHERE ads.fk_advisory = $advisoryId ";
        return self::executeQuery($query);
    }


    /**
     * Obtiene todas las asesorias donde este relacionado
     * @param $studentId int
     * @param $periodId int
     * @return \App\Model\DataResult
     */
    public function getAdvisories_ByStuden_ByPeriod($studentId, $periodId){
        $query = $this->SELECT
                ."";
        return self::executeQuery($query);
    }

    /**
     * Obtiene asesorias donde es asesor
     * @param $studentId int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisories_ByAdviser_ByPeriod($studentId, $periodId){
        $query = $this->SELECT
                ."";
        return self::executeQuery($query);
    }


    /**
     * Obtiene asesorias donde es alumno
     * @param $studentId int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisories_ByAlumn_ByPeriod($studentId, $periodId){
        $query = $this->SELECT
                    ."";
        return self::executeQuery($query);
    }

    /**
     * @param $student_id int
     * @param $subject_id int
     * @param $period_id int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisories_ByStudent_BySubject_ByPeriod( $student_id, $subject_id, $period_id )
    {
        $query = $this->SELECT.
                    "WHERE (ar.fk_student = $student_id) AND 
                    (ar.fk_subject = $subject_id) AND 
                    (ar.fk_period = $period_id)";
        return self::executeQuery($query);
    }


    /**
     * @param $advisory AdvisoryModel
     * @param $period_id int
     *
     * @return \App\Model\DataResult
     */
    public function insertAdvisory($advisory, $period_id)
    {
        $query = "INSERT INTO advisory_request(status, description, fk_student, fk_subject, fk_period)
                  VALUES(".Utils::$STATUS_PENDING.", '".$advisory->getDescription()."', 
                  ".$advisory->getStudent().", ".$advisory->getSubject().", $period_id)";
        return self::executeQuery($query);
    }


    /**
     * @param $advisory_id int
     * @param $adviser_id int
     *
     * @return \App\Model\DataResult
     */
    public function assignAdviser($advisory_id, $adviser_id)
    {
        $query = "UPDATE advisory_request 
                    SET fk_adviser = $adviser_id, 
                    date_start = NOW(), status = ".Utils::$STATUS_ENABLE."
                    WHERE advisory_id = $advisory_id";
        return self::executeQuery($query);
    }

    /**
     * @param $advisory_id int
     * @param $hour_id int
     *
     * @return \App\Model\DataResult
     */
    public function insertAdvisoryHours($advisory_id, $hour_id)
    {
        $query = "INSERT INTO advisory_schedule(fk_advisory, fk_hours, status)
                  VALUES($advisory_id, $hour_id, ".Utils::$STATUS_ENABLE.")";
        return self::executeQuery($query);
    }

    /**
     * @param $advisory_id int
     *
     * @return \App\Model\DataResult
     */
    public function getAdvisoryHours($advisory_id)
    {
        $query = "SELECT 
                      ads.advisory_schedule_id as 'id',
                      h.schedule_dh_id as 'schedule_id',
                      h2.day_hour_id as 'day_hour_id',
                      ads.fk_advisory as 'advisory_id',
                      ads.date_register as 'date_register'
                  FROM advisory_schedule ads
                  INNER JOIN schedule_days_hours h ON ads.fk_hours = h.schedule_dh_id
                  INNER JOIN day_and_hour h2 ON h.fk_day_hour = h2.day_hour_id
                  WHERE ads.fk_advisory = $advisory_id";
        return self::executeQuery($query);
    }


    /**
     * @param $advisory_id int
     * @return \App\Model\DataResult
     */
    public function finaliceAdvisory($advisory_id)
    {
        $query = "UPDATE advisory_request
                SET status =".Utils::$STATUS_FINALIZED.", date_end = NOW()
                WHERE advisory_id = $advisory_id";
        return self::executeQuery($query);
    }

    /**
     * Obtiene los asesores disponibles de una materia en un periodo y sin ser él mismo
     * @param $period_id int
     * @param $subject_id int
     * @param $student_id int
     *
     * @return \App\Model\DataResult
     */
    public function getCurrentAdvisers_ByPeriod_BySubject_IngoreStudent($period_id, $subject_id, $student_id){
        $query = "SELECT 
                      st.student_id as 'id',
                      concat(st.first_name, ' ',st.last_name) as 'name',
                      CONCAT('assets/images/',st.avatar) as 'avatar',
                      st.itson_id as 'itson_id',
                      c.career_id as 'career_id',
                      c.name as 'career_name',
                      s.schedule_id as 'schedule_id'
                  FROM student st
                  INNER JOIN user u ON st.fk_user = u.user_id
                  INNER JOIN career c ON st.fk_career = c.career_id
                  INNER JOIN schedule s ON st.student_id = s.fk_student
                  INNER JOIN schedule_subjects ss ON s.schedule_id = ss.fk_schedule
                  WHERE s.fk_period = $period_id AND ss.fk_subject = $subject_id 
                        AND st.student_id <> $student_id AND u.status = ".Utils::$STATUS_ENABLE;
        return self::executeQuery($query);
    }

    /**
     * @param $advisory_id int
     * @param $status int
     *
     * @return \App\Model\DataResult
     */
    public function changeAdvisoryStatus($advisory_id, $status){
        $query = "UPDATE advisory_request ar
                    SET status = $status
                   WHERE ar.advisory_id = $advisory_id";
        return self::executeQuery($query);
    }


//    public function getActiveAdvisories_ByPeriod($period){
//        $query = $this->SELECT
//            ."";
//        return self::executeQuery($query);
//    }
//
//    public function getFinalizedAdvisories_ByPeriod($period){
//        $query = $this->SELECT
//            ."";
//        return self::executeQuery($query);
//    }
//
//    public function getPendingdAdvisories_ByPeriod($period){
//        $query = $this->SELECT
//            ."";
//        return self::executeQuery($query);
//    }


}