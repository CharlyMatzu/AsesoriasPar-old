<?php namespace Service;

use Exceptions\BadRequestException;
use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;

use Model\User;
use Persistence\Students;
use Persistence\UsersPersistence;
use Model\Student;
use Service\UserService;
use Utils;

class StudentService{

    private $perStudents;

    public function __construct(){
        $this->perStudents = new Students();
    }

    /**
     * @param $id
     * @return array|bool|null|string
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getStudent_ById( $id ){
        $result = $this->perStudents->getStudent_ById( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener estudiante");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe estudiante");
        else
            return $result->getData();
    }

    /**
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getStudents(){
        $result = $this->perStudents->getStudents();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener usuarios");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay estudiantes");
        else
            return $result->getData();
    }

    /**
     * @param $itsonId String
     * @return \Model\DataResult
     */
    public function isItsonIdExist($itsonId){
        $result = $this->perStudents->getStudent_ByItsonId( $itsonId );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }


    //----------REGISTRAR ESTUDIANTE

    /**
     * @param $student Student
     *
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertStudent( $student ){

        //Verifica que id de itson no exista
        $result = $this->isItsonIdExist( $student->getItsonId() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al verificar id ITSON");
        else if( $result->getOperation() == true )
            throw new ConflictException( "ITSON id ya existe" );

        //Se registra usuario
        $result = $this->perStudents->insertStudent( $student );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "Ocurrio un error al registrar usuario");
    }


    /**
     * @param $student Student
     * @return array
     */
    public function updateStudent($student){
        $idUser = $student->getUser();
        $controlUser = new UserService();

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

        $result = $this->perStudents->changeStatusToDeleted( $id );
        if( $result['operation'] == Utils::$OPERATION_ERROR )
            throw new InternalErrorException("Ocurrio un error al eliminar el estudiante", $result['error']);
        else
            return Utils::makeArrayResponse(
                "Se elimino el estudiante con éxito",
                'Correcto!'
            );
    }
}

