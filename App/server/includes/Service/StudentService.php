<?php namespace Service;

use Exceptions\BadRequestException;
use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;

use Exceptions\RequestException;
use Model\Schedule;
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


//    /**
//     * @param $student Student
//     * @return array
//     */
//    public function updateStudent($student){
//        $idUser = $student->getUser();
//        $controlUser = new UserService();
//
//        $user = new User();
//        $user->setId($idUser);
//
//        $result = $controlUser->insertUser($user);
//        if ($result == false)
//            throw new ConflictException("Usuario no existe");
//        else if ($result == true) {
//            $result = $this->ifUserExist($student);
//            if ($result == true)
//                throw new ConflictException("Estudiante ya existe");
//            else if($result == false)
//                $result = $this->ifExistCareer($student->getCareer());
//            if ($result == false)
//                throw new ConflictException("Carrera no existe");
//            else if ($result == true) {
//
//                $result = $this->perStudents->updateStudent($student);
//                if ($result['operation'] == Utils::$OPERATION_ERROR)
//                    throw new InternalErrorException("Ocurrio un error al actualizar estudiante", $result['error']);
//                else
//                    return Utils::makeArrayResponse(
//                        "Se actualizo estudiente con éxito",
//                        'Correcto!'
//                    );
//            }
//        }
//        throw new ConflictException("Error!");
//    }

//    /**
//     * @param $id int
//     *
//     * @return array
//     * @throws ConflictException
//     * @throws InternalErrorException
//     */
//    public function deleteStudent( $id )
//    {
//        try{
//            $result = $this->getStudent_ById( $id );
//        }catch (RequestException $e){
//
//        }
//
//        $result = $this->perStudents->changeStatusToDeleted( $id );
//        if( $result['operation'] == Utils::$OPERATION_ERROR )
//            throw new InternalErrorException("Ocurrio un error al eliminar el estudiante", $result['error']);
//        else
//            return Utils::makeArrayResponse(
//                "Se elimino el estudiante con éxito",
//                'Correcto!'
//            );
//    }


    /**
     * Obtiene todas las horas y dias de un horario asi como las materias
     * @param $studentId int
     * @return array
     * @throws RequestException
     */
    public function getSchedule($studentId)
    {
        //Se verifica que exista estudiante
        try {
            $this->getStudent_ById($studentId);
        } catch (RequestException $e) {
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //Se obtiene horario de estudiante
        /* @var $schedule Schedule */
        $schedule = null;
        $scheduleService = new ScheduleService();
        try {
            $schedule = $scheduleService->getCurrentSchedule_ByStudentId($studentId);
            $schedule = ScheduleService::makeScheduleModel($schedule[0]);
        } catch (RequestException $e) {
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //se obtiene horas de horario
        $hours_days = null;
        try {
            $hours_days = $scheduleService->getScheduleHoursAndDays_ById($schedule->getId());
        } catch (RequestException $e) {
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //se obtiene materias (si hay)
        $subjects = null;
        try {
            $subjects = $scheduleService->getScheduleSubjects_ById($schedule->getId());
        }catch (InternalErrorException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }catch (NoContentException $e){
            //No hay problema
        }

        $student_schedule = [
            "id" => $schedule->getId(),
            "period" => $schedule->getPeriod(),
            "hours_days" => $hours_days,
            "subjects" => $subjects
        ];

        return $student_schedule;
    }


    /**
     * @param $studentId int
     * @param $schedule_hours array
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws RequestException
     */
    public function createSchedule($studentId, $schedule_hours )
    {
        //Se comprueba existencia de alumno
        $this->getStudent_ById( $studentId );

        //se envia a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->insertSchedule( $studentId, $schedule_hours );
    }

    /**
     * @param $scheduleid
     * @param $schedule_subjects
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function addScheduleSubjects($scheduleid, $schedule_subjects )
    {
        //Se comprueba existencia de horario
        $this->getSchedule( $scheduleid );

        //se envia a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->insertScheduleSubjects( $scheduleid, $schedule_subjects );
    }


}

