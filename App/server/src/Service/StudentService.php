<?php namespace App\Service;

use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NoContentException;
use App\Exceptions\Request\NotFoundException;

use App\Exceptions\Request\RequestException;
use App\Model\AdvisoryModel;
use App\Model\ScheduleModel;
use App\Persistence\Persistence;
use App\Persistence\StudentsPersistence;
use App\Model\StudentModel;
use App\Utils;

class StudentService{

    private $perStudents;

    public function __construct(){
        $this->perStudents = new StudentsPersistence();
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
            throw new InternalErrorException( "getStudent_ById","Ocurrió un error al obtener estudiante", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe estudiante");
        else
            return $result->getData()[0];
    }

    /**
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getStudents(){
        $result = $this->perStudents->getStudents();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getStudents","Ocurrió un error al obtener usuarios", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay estudiantes");
        else
            return $result->getData();
    }

    /**
     * @param $id int
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getStudents_ByCareer($id)
    {
        $result = $this->perStudents->getStudents_ByCareer( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("getStudents_ByCareer","Ocurrió un error al obtener usuarios por carrera", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay estudiantes");
        else
            return $result->getData();
    }

    /**
     * @param $student_data string
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function searchStudents_ByData($student_data)
    {
        $result = $this->perStudents->searchStudents( $student_data );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("searchStudents_ByData","Ocurrió un error al obtener usuarios", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay estudiantes");

        return $result->getData();
    }

    /**
     * @param $itson_Id String
     *
     * @return \App\Model\DataResult
     * @throws InternalErrorException
     */
    public function isItsonIdExist($itson_Id){
        $result = $this->perStudents->getStudent_ByItsonId( $itson_Id );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }


    //----------REGISTRAR ESTUDIANTE

    /**
     * @param $student StudentModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertStudent( $student ){

        //Verifica que id de itson no exista
        $result = $this->isItsonIdExist( $student->getItsonId() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertStudents","Ocurrió un error al verificar id ITSON", $result->getErrorMessage());
        else if( $result->getOperation() == true )
            throw new ConflictException( "ITSON id ya existe" );

        //Se registra usuario
        $result = $this->perStudents->insertStudent( $student );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertStudents","Ocurrió un error al registrar usuario", $result->getErrorMessage());
    }


    /**
     * @param $email String
     * @param $student StudentModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws \App\Exceptions\Persistence\TransactionException
     * @throws RequestException
     */
    public function updateStudent( $email, $student ){

        //Verificar si estudiante existe
        $student_aux = $this->getStudent_ById( $student->getId() );

        //Obtenemos su usuario
        $userServ = new UserService();
        $user = $userServ->getUser_ByStudentId( $student->getId() );

        StudentsPersistence::initTransaction();

        try{
            $userServ->updateUserEmail( $user['id'], $email );
        }catch (RequestException $e){
            StudentsPersistence::rollbackTransaction();
            throw new RequestException( $e->getMessage(), $e->getStatusCode() );
        }

        //Verificamos si cambio el ID de itson
        if( $student_aux['itson_id'] != $student->getItsonId() ){
            $result = $this->isItsonIdExist( $student->getItsonId() );
            if( Utils::isError( $result->getOperation() ) ) {
                StudentsPersistence::rollbackTransaction();
                throw new InternalErrorException("updateStudent", "Ocurrió un error al verificar id ITSON", $result->getErrorMessage());
            }
            else if( $result->getOperation() == true ) {
                StudentsPersistence::rollbackTransaction();
                throw new ConflictException("ITSON id ya existe");
            }
        }

        //Verificamos si cambio carrera
        if( $student_aux['career_id'] != $student->getCareer() ){
            $careerService = new CareerService();
            $careerService->getCareer_ById( $student->getCareer() );
        }

        //Se actualizan datos de alumno
        $result = $this->perStudents->updateStudent( $student );
        if( Utils::isError( $result->getOperation() ) ) {
            StudentsPersistence::rollbackTransaction();
            throw new InternalErrorException("updateStudent", "Ocurrió un error al actualizar estudiante", $result->getErrorMessage());
        }

        StudentsPersistence::commitTransaction();
    }


    /**
     * Obtiene todas las horas y dias de un horario asi como las materias
     *
     * @param $studentId int
     *
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws RequestException
     */
    public function getCurrentStudentSchedule_ById($studentId)
    {
        //TODO: mover todo a un método en ScheduleService

        //Se verifica que exista estudiante
        $this->getStudent_ById($studentId);

        //Se obtiene horario de estudiante
        /* @var $schedule ScheduleModel */
        $scheduleService = new ScheduleService();
        $schedule = $scheduleService->getCurrentSchedule_ByStudentId($studentId);
        $schedule = ScheduleService::makeScheduleModel($schedule);

        //se obtiene horas de horario
        $hours_days = array();
        try {
            $hours_days = $scheduleService->getScheduleHours_BySchedule( $schedule->getId() );
            //Si no tiene horas, no hay problema
        }catch (InternalErrorException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
            //Si no hay horas, no hay problema
        }catch (NoContentException $e){}

        //se obtiene materias (si hay)
        $subjects = array();
        try {
            $subjects = $scheduleService->getScheduleSubjects_BySchedule( $schedule->getId() );
        }catch (InternalErrorException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
            //Si no tiene materias, no hay problema
        }catch (NoContentException $e){}

        $student_schedule = [
            "id" => $schedule->getId(),
            "status" => $schedule->getStatus(),
            "period" => $schedule->getPeriod(),
            "days_hours" => $hours_days,
            "subjects" => $subjects
        ];

        return $student_schedule;
    }


    /**
     * @param $studentId int
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws RequestException
     */
    public function createSchedule($studentId )
    {
        //Se comprueba existencia de alumno
        $this->getStudent_ById( $studentId );

        //se envía a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->insertSchedule( $studentId );
    }


    //----------------------- ADVISORIES

    /**
     * @param $advisory AdvisoryModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws NotFoundException
     */
    public function createAdvisoryCurrentPeriod($advisory)
    {
        //Se comprueba existencia de alumno
        $this->getStudent_ById( $advisory->getStudent() );

        //Se obtiene periodo actual
        $advisoryService = new AdvisoryService();
        $advisoryService->insertAdvisory_CurrentPeriod( $advisory );
    }

    /**
     * @param $student_id int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function getCurrentAdvisories_ByStudentId($student_id)
    {
        //Se comprueba existencia de alumno
        $this->getStudent_ById( $student_id );

        //Se obtiene periodo actual
        $scheduleService = new ScheduleService();
        return $scheduleService->getCurrentSchedule_ByStudentId( $student_id );
    }



    public static function makeStudentModel( $data ){
        $student = new StudentModel();
        //setting data
        $student->setId( $data['id'] );
        $student->setItsonId( $data['itson_id'] );
        $student->setFirstName( $data['first_name'] );
        $student->setLastName( $data['last_name'] );
        $student->setPhone( $data['phone'] );
        $student->setFacebook( $data['facebook'] );
//        $student->setAvatar( $data['avatar'] );
        $student->setRegisterDate( $data['date_register'] );

//        $student->setStatus( $data['status'] );
        $student->setUser( $data['user_id'] );
        $student->setCareer( $data['career_id'] );

        //Returning object
        return $student;
    }




}

