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
                        
                        s.fk_user as 'user_id',
                        u.email as 'user_email',
                        u.status as 'status',
                        
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
                    WHERE s.fk_user = $id AND (u.status = '".Utils::$STATUS_ACTIVE."' AND u.fk_role = '".Utils::$ROLE_BASIC."')";
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

    /**
     * @param $career_id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStudents_ByCareer($career_id)
    {
        $query =    $this->SELECT."
                    WHERE c.career_id = $career_id";
        //Obteniendo resultados
        return $this->executeQuery($query);
    }


    //------------------REGISTROS


    /**
     * @param $student StudentModel
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function insertStudent( $student ){
        $query = "INSERT INTO student(itson_id, first_name, last_name, phone, facebook, fk_user , fk_career)
                  VALUES(
                      '".$student->getItsonId()."',
                      '".$student->getFirstName()."', 
                      '".$student->getLastname()."',
                      '".$student->getPhone()."',
                      '".$student->getFacebook()."', 
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
                      s.facebook = '".$student->getFacebook()."', 
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
//                         SET s.status = '$status'
//                         WHERE s.student_id = " .$idStudent ;
//        return  self::executeQuery($query);
//    }








}