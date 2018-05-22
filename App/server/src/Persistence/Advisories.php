<?php namespace App\Persistence;


class Advisories extends Persistence{

    public function __construct(){}

    private $selectComun = "SELECT
                    a.pk_id as 'id',
                    a.register_date as 'register_date',
                    a.status as 'status',
                    e_al.pk_id as 'student',
                    e_as.pk_id as 'adviser',
                    s.name as 'subject'
                    FROM adviser_request a
                    INNER JOIN student e_al ON e_al.pk_id = a.fk_student
                    INNER JOIN student e_as ON e_as.pk_id = a.fk_adviser
                    INNER JOIN schedule_subject hm ON hm.pk_id = a.fk_subject
                    INNER JOIN subject s ON s.pk_id = hm.fk_subject
                    INNER JOIN schedule h ON h.pk_id = hm.fk_schedule ";


    public function getAdvisories(){
        $query = $this->selectComun;
        return self::executeQuery($query);
    }


    public function getAdvisories_ByPeriod($periodId){
        $query = $this->selectComun
                    ."";
        return self::executeQuery($query);
    }


    public function getAdvisories_ByAdviser($studentId){
        $query = $this->selectComun
            ."";
        return self::executeQuery($query);
    }


    public function getAdvisories_ByStudent($studentId){
        $query = $this->selectComun
            ."";
        return self::executeQuery($query);
    }


    public function getEnableAdvisories($studentId){
        $query = $this->selectComun
            ."";
        return self::executeQuery($query);
    }

    public function getDisabledAdvisories($studentId){
        $query = $this->selectComun
            ."";
        return self::executeQuery($query);
    }
}