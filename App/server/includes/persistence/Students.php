<?php namespace Persistence;

use Objects\Student;

class Students extends Persistence{

    public function __construct(){}

    private $campos = "SELECT
                        e.pk_id as 'id', 
                        e.itson_id as 'itson_id', 
                        e.first_name as 'first_name', 
                        e.last_name as 'last_name', 
                        e.phone as 'phone', 
                        e.facebook as 'facebook', 
                        e.avatar as 'avatar', 
                        e.date_register as 'date_register',
                        e.status as 'status',
                        e.fk_user as 'user_id', 
                        c.pk_id as 'career_id'
                        FROM student e";


    public function getStudent_ById($id){
        $query =    $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        WHERE e.pk_id = ".$id;
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudents(){
        $query =    $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudent_ByUserId( $id ){
        $query = $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        WHERE e.fk_user =".$id;
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudent_byName( $name ){
        $query = $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        WHERE e.first_name LIKE '%$name%' OR e.last_name LIKE '%$name%' ";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function ifExistCareer( $career ){
        $query = " SELECT c.name FROM career c
                   WHERE c.pk_id =".$career;
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    /**
     * @param $student Student
     * @return array|bool|null
     */
    public function ifUserExist( $student ){
        $query = " SELECT s.pk_id FROM student s
                   WHERE s.fk_user =".$student->getUser();
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudent_byCareer ( $career ){
        $query = $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        WHERE c.name LIKE '%$career%'";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudent_bySubject ( $subject ){
        $query = $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        INNER JOIN subject s ON c.pk_id = s.fk_career 
                        WHERE s.name LIKE '%$subject%'";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudent_byItsonId( $itsonId ){
        $query = $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        WHERE e.itson_id LIKE '%$itsonId%'";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    public function getStudent_byAdvisor( $advisor ){
        $query = $this->campos."
                        INNER JOIN career c ON c.pk_id = e.fk_career
                        WHERE e.itson_id LIKE '%$advisor%'";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    //------------------REGISTROS


    /**
     * @param $student Student
     * @return array|bool|null
     */
    public function insertStudent( $student ){
        $query = "INSERT INTO student(itson_id, first_name, last_name, fk_user , fk_career)
                  VALUES(
                      '".$student->getItsonId()."',
                      '".$student->getFirstName()."', 
                      '".$student->getLastname()."', 
                      ".$student->getUser().", 
                      ".$student->getCareer().")";
        return  self::executeQuery($query);
    }

    /**
     * @param $student Student
     * @return array|bool|null
     */
    public function updateStudent( $student ){
        $query = "UPDATE  student s 
                          SET s.itson_id = '".$student->getItsonId()."', s.first_name = '".$student->getFirstName()."', s.last_name = '".$student->getLastName()."',
                          s.status = '".$student->getStatus()."', s.fk_user = '".$student->getUser()."', s.fk_career = '".$student->getCareer()."'
                          WHERE s.pk_id = ".$student->getId();
        return  self::executeQuery($query);
    }

    /**
     * @param $idStudent int
     * @return array|bool|null
     */
    public function deleteStudent( $idStudent ){
        $query = "UPDATE student s
                         SET s.status = 0    
                         WHERE s.pk_id = " .$idStudent ;
        return  self::executeQuery($query);
    }
}
?>