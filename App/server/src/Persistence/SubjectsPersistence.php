<?php namespace App\Persistence;

use App\Model\Subject;
use App\Utils;

class SubjectsPersistence extends Persistence{
    public function __construct(){}

    private $campos = "SELECT
                        s.subject_id as 'id',
                        s.name as 'name',
                        s.short_name as 'short_name',
                        s.semester as 'semester',
                        s.date_register as 'date_register',
                        s.description as 'description',
                        s.status as 'status',
                        p.year as 'plan_year',
                        s.fk_plan as 'plan_id',
                        c.name as 'career_name',
                        s.fk_career as 'career_id'
                        FROM subject s";

    /**
     * @return \App\Model\DataResult
     */
    public function getSubjects(){
        $query = $this->campos."
                        INNER JOIN career c ON s.fk_career = c.career_id
                        INNER JOIN plan p ON s.fk_plan = p.plan_id 
                        ORDER BY s.semester, s.name";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $subjectID
     * @return \App\Model\DataResult
     */
    public function getSubject_ById($subjectID){
        $query = $this->campos."
                     INNER JOIN career c ON s.fk_career = c.career_id
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.subject_id = ".$subjectID;
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $subject_career
     * @param $subject_semester
     * @param $subject_plan
     *
     * @return \App\Model\DataResult
     */
    public function getSubject_SearchFilter($subject_career, $subject_semester, $subject_plan){

        //filtro de solo carrera
        if($subject_career != 0  && $subject_semester == 0  && $subject_plan == 0){
            $query = $this->campos."
            INNER JOIN career c ON s.fk_career = c.career_id
            INNER JOIN plan p ON s.fk_plan = p.plan_id
            WHERE s.fk_career =".$subject_career;

        }
        //filtro de solo semestre
        else if($subject_semester != 0 && $subject_career ==0 && $subject_plan == 0){
            $query = $this->campos."
            INNER JOIN career c ON s.fk_career = c.career_id
            INNER JOIN plan p ON s.fk_plan = p.plan_id
            WHERE s.semester =".$subject_semester;

        }
        //filtro de solo plan
        else if($subject_plan != 0 && $subject_career == 0 && $subject_semester == 0){
            $query = $this->campos."
            INNER JOIN career c ON s.fk_career = c.career_id
            INNER JOIN plan p ON s.fk_plan = p.plan_id
            WHERE s.fk_plan =".$subject_plan;

        }
        //filtro de carrera y semestre
        else if($subject_career != 0  && $subject_semester != 0  && $subject_plan == 0 ){
            $query = $this->campos."
            INNER JOIN career c ON s.fk_career = c.career_id
            INNER JOIN plan p ON s.fk_plan = p.plan_id
            WHERE s.fk_career =".$subject_career." 
            AND s.semester =".$subject_semester;

        }
        //filtro de carrera y plan
        else if($subject_semester == 0 && $subject_career != 0 && $subject_plan != 0){
            $query = $this->campos."
            INNER JOIN career c ON s.fk_career = c.career_id
            INNER JOIN plan p ON s.fk_plan = p.plan_id
            WHERE s.fk_career =".$subject_career." 
            AND s.fk_plan =".$subject_plan;

        }
        //filtro de solo semestre y plan
        else if($subject_plan != 0 && $subject_semester != 0 && $subject_career== 0){
            $query = $this->campos."
            INNER JOIN career c ON s.fk_career = c.career_id
            INNER JOIN plan p ON s.fk_plan = p.plan_id
            WHERE s.semester =".$subject_semester."
            AND s.fk_plan =".$subject_plan;

        }
        //todos los filtros
        else{
        
        $query = $this->campos."
                     INNER JOIN career c ON s.fk_career = c.career_id
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.fk_career = ".$subject_career." 
                     AND s.semester =".$subject_semester."
                     AND s.fk_plan =".$subject_plan;
        }
        //Obteniendo resultados
        return self::executeQuery($query);
    }
    

    /**
     * @param $name
     * @return \App\Model\DataResult
     */
    public function getSubject_ByName($name){
        $query = $this->campos."
                     INNER JOIN career c ON s.fk_career = c.career_id
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.name = '$name'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $shortName
     * @return \App\Model\DataResult
     */
    public function getSubject_ByShortName($shortName)
    {
        $query = $this->campos."
                     INNER JOIN career c ON s.fk_career = c.career_id
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.short_name = '$shortName'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $name
     * @return \App\Model\DataResult
     */
    public function searchSubjects_ByName($name){
        $query = $this->campos."
                     INNER JOIN career c ON s.fk_career = c.career_id
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.name LIKE '%$name%'  OR s.short_name LIKE '%$name%'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $name String
     * @param $plan int
     * @param $career int
     *
     * @return \App\Model\DataResult
     */
    public function getSubject_ByName_ShortName($name, $plan, $career, $subject_id = null){


        $query = $this->campos."
                     INNER JOIN career c ON s.fk_career = c.career_id
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                      WHERE (s.fk_career = $career AND s.fk_plan = $plan) AND
                            (s.name = '$name' OR s.short_name = '$name') ";

        //Si se define un ID, se agrega al query para omitirlo en la busqueda
        if( $subject_id != null )
            $query .= "AND s.subject_id <> $subject_id";

        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $semester int
     * @return \App\Model\DataResult
     */
    public function getSubjects_BySemester( $semester )
    {
        $query = $this->campos."
                    INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.semester = ".$semester." ";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $planID int
     * @return \App\Model\DataResult
     */
    public function getSubjects_ByPlan( $planID )
    {
        $query = $this->campos."
                    INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.fk_plan = $planID";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $careerID
     * @return \App\Model\DataResult
     */
    public function getSubjects_ByCareer($careerID )
    {
        $query = $this->campos."
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE c.career_id = $careerID";

        //Obteniendo resultados
        return self::executeQuery($query);
    }



    /**
     * @param $name string nombre de la materia
     * @param $careerID int Career ID
     * @return \App\Model\DataResult
     */
    public function getSubject_ByName_Career($name, $careerID)
    {
        $query = $this->campos."
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE (s.name LIKE '%$name%' OR s.short_name LIKE '%$name%') AND s.fk_career = $careerID";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $name string nombre de la materia
     * @param $careerID int Career ID
     * @param $planID int plan ID
     * @return \App\Model\DataResult
     */
    public function getSubject_ByName_Career_Plan($name, $careerID, $planID)
    {
        $query = $this->campos."
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE (s.name LIKE '%$name%' OR s.short_name LIKE '%$name%') AND s.fk_career = $careerID AND s.fk_plan = $planID";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $careerID int Career ID
     * @param $planID int plan ID
     * @return \App\Model\DataResult
     */
    public function getSubject_ByCareer_Plan($careerID, $planID)
    {
        $query = $this->campos."
                     INNER JOIN plan p ON s.fk_plan = p.plan_id
                     WHERE s.fk_career = $careerID AND s.fk_plan = $planID";
        //Obteniendo resultados
        return self::executeQuery($query);
    }


    /** ----------------------------- Nuevo --------------------------------------- */







    /**
     * @param $subject Subject
     * @return \App\Model\DataResult
     */
    public function insertSubject( $subject )
    {
        $query = "INSERT INTO subject (name, short_name, description, semester, fk_plan, fk_career) 
                      VALUES ('".$subject->getName()."','".$subject->getShortName()."','".$subject->getDescription()."',".$subject->getSemester().",".$subject->getPlan().",".$subject->getCareer().")";
        return  self::executeQuery($query);
    }

    /**
     * @param $subject Subject
     * @return \App\Model\DataResult
     */
    public function updateSubject($subject)
    {
        $query = "UPDATE subject s 
                      SET s.name = '".$subject->getName()."', s.short_name = '".$subject->getShortName()."', s.description = '".$subject->getDescription()."', s.semester = ".$subject->getSemester().", s.fk_plan = '".$subject->getPlan()."', s.fk_career = ".$subject->getCareer()."  
                      WHERE s.subject_id = ".$subject->getId();
        return  self::executeQuery($query);
    }

    /**
     * @param $subjectID
     * @return \App\Model\DataResult
     */
    public function changeStatusToDeleted($subjectID ){
        $query = "UPDATE subject
                    SET status = ".Utils::$STATUS_DISABLE." 
                    WHERE subject_id = $subjectID";
        return  self::executeQuery($query);
    }

    /**
     * @param $subjectID
     * @return \App\Model\DataResult
     */
    public function changeStatusToEnable($subjectID ){
        $query = "UPDATE subject
                    SET status = ".Utils::$STATUS_ENABLE." 
                    WHERE subject_id = $subjectID";
        return  self::executeQuery($query);
    }

    /**
     * @param $subjectID int
     *
     * @return \App\Model\DataResult
     */
    public function deleteSubject($subjectID ){
        $query = "DELETE FROM subject
                  WHERE subject_id = $subjectID";
        return  self::executeQuery($query);
    }


    /**
     * @param $subjectID
     * @return \App\Model\DataResult
     */
//    public function changeStatusToEnable( $subjectID ){
//        $query = "UPDATE subject
//                    SET status = ".Utils::$STATUS_ENABLE."
//                    WHERE subject_id = $subjectID";
//        return self::executeQuery($query);
//    }


    //----------------------
    // MATERIAS SIMILARES
    //----------------------

//    /**
//     * @param $sub_1 int
//     * @param $sub_2 int
//     * @return \App\Model\DataResult
//     */
//    public function setSubjectRelation($sub_1, $sub_2)
//    {
//        $query = "INSERT INTO subject_similary(fk_subject_1, fk_subject_2)
//                  VALUES($sub_1, $sub_2)";
//        return  self::executeQuery($query);
//    }
//
//    /**
//     * @param $relation_id int
//     * @return \App\Model\DataResult
//     */
//    public function deleteSubjectRelation($relation_id)
//    {
//        $query = "DELETE FROM subject_similary
//                  WHERE pk_similary = $relation_id";
//        return  self::executeQuery($query);
//    }





//    /**
//     * @param $id
//     * @return \Objects\DataResult
//     */
//    public function getSubjects_ByScheduleId( $id ){
//        $query = $this->campos."
//                        INNER JOIN schedule_subjects hm ON hm.fk_subject = s.subject_id
//                        INNER JOIN schedule h ON h.pk_id = hm.fk_schedule
//                        INNER JOIN career c ON m.fk_career = c.career_id
//                        WHERE hm.fk_schedule = ".$id."
//                        ORDER BY s.semester, s.name ASC";
//        //Obteniendo resultados
//        return self::executeQuery($query);
//    }
//
//    /**
//     * @param $careerId
//     * @return \Objects\DataResult
//     */
//    public function getSubjects_ByCareerId( $careerId ){
//        $query = $this->campos."
//                        INNER JOIN career c ON s.fk_career = c.career_id
//                        WHERE c.career_id = ".$careerId."
//                        ORDER BY s.semester, s.name ASC";
//        //Obteniendo resultados
//        return self::executeQuery($query);
//    }
//
//    /**
//     * @param $careerName
//     * @return \Objects\DataResult
//     */
//    public function getSubjects_ByCareerName( $careerName ){
//        $query = $this->campos."
//                        INNER JOIN career c ON s.fk_career = c.career_id
//                        WHERE (c.name = '".$careerName."' OR c.short_name = '".$careerName."')
//                        ORDER BY s.semester, s.name ASC";
//        //Obteniendo resultados
//        return self::executeQuery($query);
//    }


    //-----------------
    // DE HORARIOS
    //-----------------

    /**
     * @param $idStudent
     * @return \App\Model\DataResult
     */
//    public function getAvailScheduleSubs_SkipStudent( $idStudent, $idPeriod ){
//        //Para que no se repita
//        $select = str_replace("SELECT", "SELECT DISTINCT", $this->campos);
//
//        $query = $select."
//                    INNER JOIN career c ON s.fk_career = c.career_id
//                    INNER JOIN schedule_subjects hm ON hm.fk_subject = s.subject_id
//                    INNER JOIN schedule h ON h.pk_id = hm.fk_schedule
//                    WHERE (h.fk_period = ".$idPeriod.") AND (h.fk_student <> ".$idStudent.")
//                    ORDER BY s.semester, s.name ASC";
//        return self::executeQuery($query);
//    }



}
