<?php namespace App\Persistence;

use App\Model\StudentModel;
use App\Utils;

class StudentsPersistence extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT
                        s.student_id as 'id', 
                        s.itson_id as 'itson_id', 
                        s.first_name as 'first_name', 
                        s.last_name as 'last_name', 
                        s.phone as 'phone', 
                        s.facebook as 'facebook', 
                        s.date_register as 'date_register',
                        s.status as 'status',
                        
                        s.fk_user as 'user_id',
                        u.email as 'user_email',
                        u.status as 'user_status',
                        
                        c.career_id as 'career_id',
                        c.name as 'career_name',
                        c.short_name as 'career_short_name'
                        FROM student s
                        INNER JOIN user u ON s.fk_user = u.user_id
                        INNER JOIN career c ON c.career_id = s.fk_career ";


    /**
     * @param $id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStudent_ById($id){
        $query =    $this->SELECT."
                        WHERE s.student_id = ".$id;
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStudents(){
        $query =    $this->SELECT;
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    /**
     * @param $id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStudent_ByUserId($id)
    {
        $query =    $this->SELECT."
                    WHERE s.fk_user = $id AND u.fk_role = '".Utils::$ROLE_BASIC."'";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    /**
     * @param $id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStudent_ByEnabledUserId($id)
    {
        $query =    $this->SELECT."
                    WHERE s.fk_user = $id AND (u.status = '".Utils::$STATUS_ENABLE."' AND u.fk_role = '".Utils::$ROLE_BASIC."')";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    /**
     * @param $data string
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function searchStudents($data)
    {
        $query =    $this->SELECT."
                    WHERE (s.first_name LIKE '%$data%') OR
                          (s.last_name LIKE '%$data%') OR 
                          (s.phone LIKE '%$data%') OR 
                          (s.itson_id LIKE '%$data%')";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

    /**
     * @param $itsonId
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStudent_ByItsonId($itsonId){
        $query =    $this->SELECT."
                    WHERE s.itson_id = '$itsonId'";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }

//    /**
//     * @param $id int
//     * @return \App\Model\DataResult
//     */
//    public function getStudent_ByUserId( $id ){
//        $query = $this->SELECT."
//                        INNER JOIN career c ON c.career_id = s.fk_career
//                        WHERE s.fk_user =".$id;
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }

//    /**
//     * @param $name String
//     * @return \Objects\DataResult
//     */
//    public function getStudent_LikeName($name ){
//        $query = $this->SELECT."
//                        INNER JOIN career c ON c.career_id = s.fk_career
//                        WHERE s.first_name LIKE '%$name%' OR s.last_name LIKE '%$name%' ";
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }

//    public function ifExistCareer( $career ){
//        $query = " SELECT c.name FROM career c
//                   WHERE c.career_id =".$career;
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }


//    public function ifUserExist( $student ){
//        $query = " SELECT s.student_id FROM student s
//                   WHERE s.fk_user =".$student->getUser();
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }


//    public function getStudent_byCareer ( $career ){
//        $query = $this->SELECT."
//                        INNER JOIN career c ON c.career_id = s.fk_career
//                        WHERE c.name LIKE '%$career%'";
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }


//    public function getStudent_bySubject ( $subject ){
//        $query = $this->SELECT."
//                        INNER JOIN career c ON c.career_id = s.fk_career
//                        INNER JOIN subject s ON c.career_id = s.fk_career
//                        WHERE s.name LIKE '%$subject%'";
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }

//    public function getStudent_byItsonId( $itsonId ){
//        $query = $this->SELECT."
//                        INNER JOIN career c ON c.career_id = s.fk_career
//                        WHERE s.itson_id LIKE '%$itsonId%'";
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }

//    public function getStudent_byAdvisor( $advisor ){
//        $query = $this->SELECT."
//                        INNER JOIN career c ON c.career_id = s.fk_career
//                        WHERE s.itson_id LIKE '%$advisor%'";
//        //Obteniendo resultados
//        return $this->executeQuery($query);
//    }

    //------------------REGISTROS


    /**
     * @param $student StudentModel
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function insertStudent( $student ){
        $query = "INSERT INTO student(itson_id, first_name, last_name, phone, fk_user , fk_career)
                  VALUES(
                      '".$student->getItsonId()."',
                      '".$student->getFirstName()."', 
                      '".$student->getLastname()."',
                      '".$student->getPhone()."', 
                      ".$student->getUser()->getId().", 
                      ".$student->getCareer()->getId().")";
        return  self::executeQuery($query);
    }

    /**
     * @param $student StudentModel
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function updateStudent( $student ){
        $query = "UPDATE  student s 
                      SET s.itson_id = '".$student->getItsonId()."',
                      s.first_name = '".$student->getFirstName()."', 
                      s.last_name = '".$student->getLastName()."',
                      s.phone = '".$student->getPhone()."', 
                      s.fk_career = '".$student->getCareer()."'
                  WHERE s.student_id = ".$student->getId();
        return  self::executeQuery($query);
    }

//    /**
//     * @param $idStudent int
//     * @param $status int
//     * @return \App\Model\DataResult
//     */
//    public function changeStatus($idStudent, $status ){
//        $query = "UPDATE student s
//                         SET s.status = $status
//                         WHERE s.student_id = " .$idStudent ;
//        return  self::executeQuery($query);
//    }








}