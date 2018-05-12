<?php namespace Control;

use Exceptions\BadRequestException;
use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;

use Objects\User;
use Persistence\Students;
use Persistence\Users;
use Objects\Student;
use Control\UserControl;
use Utils;

class StudentControl{

    private $perStudents;

    public function __construct(){
        $this->perStudents = new Students();
    }

    /**
     * @param $id
     * @return array|bool|null|string
     */
    public function getStudent_ById( $id ){
        $result = $this->perStudents->getStudent_ById( $id );
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    public function getStudents(){
        $result = $this->perStudents->getStudents();
        if($result['operation'] == Utils::$OPERATION_SUCCESS )
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    public function ifUserExist($student){
        $result = $this->perStudents->ifUserExist($student);
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }


    /**
     * @param $name
     * @return array|bool|null|string
     */
    public function getStudent_byName( $name ){
        $result = $this->perStudents->getStudent_byName( $name );
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    public function ifExistCareer( $career ){
        $result = $this->perStudents->ifExistCareer( $career );
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation']  == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    /**
     * @param $career
     * @return array|bool|null|string
     */
    public function getStudent_byCareer ( $career ){
        $result = $this->perStudents->getStudent_byCareer( $career);
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    /**
     * @param $subject
     * @return array|bool|null|string
     */
    public function getStudent_bySubject ( $subject ){
        $result = $this->perStudents->getStudent_bySubject( $subject );
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    /**
     * @param $itson_id
     * @return array|bool|null|string
     */
    public function getStudent_byItsonId( $itson_id ){
        $result = $this->perStudents->getStudent_byItsonId( $itson_id );
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    public function getStudent_byAdvisors( $advisor ){
        $result = $this->perStudents->getStudent_byAdvisor( $advisor);
        if($result['operation'] == Utils::$OPERATION_EMPTY)
            return false;
        else if( $result['operation'] == Utils::$OPERATION_RESULT)
            return true;
        else
            return $result;
    }

    /**
     * @param $search_by
     * @param $search
     * @return array
     */
    public function search_Student( $search_by, $search ){
        if ($search_by === "name") {
            $result = $this->getStudent_byName($search);
            if ($result == false)
                throw new ConflictException("Usuario no existe por nombre");
        } else if ($search_by === "itson_id") {
            $result = $this->getStudent_byItsonId($search);
            if ($result == false)
                throw new ConflictException("Usuario no existe por itson_id");
        } else{
            $response = [
                "result" => "Error en los datos",
                "message" => "Verifique los datos"
            ];
            return $response;
        }

        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al registrar usuario", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Busqueda exitosa",
                $result
            );
    }

    /**
     * @param $search_by
     * @param $search
     * @return array
     */
    public function getStudent_Admin( $search_by, $search ){
        if ($search_by === "career") {
            $result = $this->getStudent_byCareer($search);
            if ($result == false)
                throw new ConflictException("Estudiante no existe por carrera");
        } else if ($search_by === "subject") {
            $result = $this->getStudent_bySubject($search);
            if ($result == false)
                throw new ConflictException("Estudiante no existe por materia");
        } else if( $search_by === 'students'){
            $result = $this->getStudents();
            if ($result == false)
                throw new ConflictException("estudiantes no existen");
        }else{
            $response = [
                "result" => "Error en los datos",
                "message" => "Verifique los datos"
            ];
            return $response;
        }

        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al registrar estudiante", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Busqueda exitosa",
                $result
            );

    }

    /**
     * @param $id
     * @return bool|string
     */
    public function isStudentExist_ById($id){
        $result = $this->perStudents->getStudent_ById( $id );
        if($result['operation'] == Utils::$OPERATION_SUCCESS )
            return true;
        else if( $result == Utils::$OPERATION_RESULT)
            return false;
        else
            return $result;
    }

    /**
     * @param $id
     * @return array|bool|null|string
     */
    public function getStuden_ByUserId( $id ){
        $result = $this->perStudents->getStudent_ByUserId( $id );
        if($result['operation'] == Utils::$OPERATION_SUCCESS )
            return true;
        else if( $result == Utils::$OPERATION_RESULT)
            return false;
        else
            return $result;
    }

    //----------REGISTRAR ESTUDIANTE

    /**
     * @param $student Student
     * @return array
     */
    public function insertStudent( $student ){
        $idUser = $student->getUser();
        $controlUser = new UserControl();

        $user = new User();
        $user->setId($idUser);

        $result = $controlUser->insertUser($user);
        if ($result == false)
            throw new ConflictException("Usuario no existe");
        else if ($result == true) {
            $result = $this->ifUserExist($student);
            if ($result == true)
                throw new ConflictException("Estudiante ya existe");
            else if($result == false)
                $result = $this->ifExistCareer($student->getCareer());
                if ($result == false)
                    throw new ConflictException("Carrera no existe");
                else if ($result == true) {
                    //verificar si el role es estudiante
                    $result = $this->perStudents->insertStudent($student);
                    if( $result['operation'] == Utils::$OPERATION_ERROR )
                        throw new InternalErrorException("Ocurrio un error al agregar estudiante", $result['error']);
                    else
                        return Utils::makeArrayResponse(
                            "Se agrego estudiente con éxito",
                            'Correcto!'
                        );
            }
        }
        throw new ConflictException("Error!");
    }


    /**
     * @param $student Student
     * @return array
     */
    public function updateStudent($student){
        $idUser = $student->getUser();
        $controlUser = new UserControl();

        $user = new User();
        $user->setId($idUser);

        $result = $controlUser->insertUser($user);
        if ($result == false)
            throw new ConflictException("Usuario no existe");
        else if ($result == true) {
            $result = $this->ifUserExist($student);
            if ($result == true)
                throw new ConflictException("Estudiante ya existe");
            else if($result == false)
                $result = $this->ifExistCareer($student->getCareer());
            if ($result == false)
                throw new ConflictException("Carrera no existe");
            else if ($result == true) {

                $result = $this->perStudents->updateStudent($student);
                if ($result['operation'] == Utils::$OPERATION_ERROR)
                    throw new InternalErrorException("Ocurrio un error al actualizar estudiante", $result['error']);
                else
                    return Utils::makeArrayResponse(
                        "Se actualizo estudiente con éxito",
                        'Correcto!'
                    );
            }
        }
        throw new ConflictException("Error!");
    }

    /**
     * @param $id int
     * @return array
     */
    public function deleteStudent( $id )
    {
        $result = $this->getStudent_ById( $id );

        if ($result == false)
            throw new ConflictException("Usuario no existe");

        $result = $this->perStudents->deleteStudent( $id );
        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al eliminar el estudiante", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Se elimino el estudiante con éxito",
                'Correcto!'
            );
    }
}

