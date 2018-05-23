<?php namespace App\Persistence;


class AdvisoriesPersistence extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT
                        ar.advisory_id as 'id',
                        ar.date_register as 'register_date',
                        ar.status as 'status',
                        s_alum.student_id as 'student_id',
                        s_advi.student_id as 'adviser_id',
                        s.name as 'subject'
                    FROM advisory_request ar
                    INNER JOIN student s_alum ON s_alum.student_id = ar.fk_student
                    INNER JOIN student s_advi ON s_advi.student_id = ar.fk_adviser
                    INNER JOIN schedule_subjects hm ON hm.schedule_subject_id = ar.fk_subject
                    INNER JOIN subject s ON s.subject_id = hm.fk_subject
                    INNER JOIN schedule h ON h.schedule_id = hm.fk_schedule ";


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
            "WHERE h.fk_period = $periodId";
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


    public function getActiveAdvisories_ByPeriod($period){
        $query = $this->SELECT
            ."";
        return self::executeQuery($query);
    }

    public function getFinalizedAdvisories_ByPeriod($period){
        $query = $this->SELECT
            ."";
        return self::executeQuery($query);
    }

    public function getPendingdAdvisories_ByPeriod($period){
        $query = $this->SELECT
            ."";
        return self::executeQuery($query);
    }
}