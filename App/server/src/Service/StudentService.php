<?php namespace App\Service;

use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;

use App\Exceptions\RequestException;
use App\Model\ScheduleModel;
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
            throw new InternalErrorException( "getStudent_ById","Ocurrio un error al obtener estudiante", $result->getErrorMessage());
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
            throw new InternalErrorException("getStudents","Ocurrio un error al obtener usuarios", $result->getErrorMessage());
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
            throw new InternalErrorException("searchStudents_ByData","Ocurrio un error al obtener usuarios", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No hay estudiantes");

        return $result->getData();
    }

    /**
     * @param $itsonId String
     * @return \App\Model\DataResult
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
     * @param $student StudentModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertStudent( $student ){

        //Verifica que id de itson no exista
        $result = $this->isItsonIdExist( $student->getItsonId() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertStudents","Ocurrio un error al verificar id ITSON", $result->getErrorMessage());
        else if( $result->getOperation() == true )
            throw new ConflictException( "ITSON id ya existe" );

        //Se registra usuario
        $result = $this->perStudents->insertStudent( $student );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "insertStudents","Ocurrio un error al registrar usuario", $result->getErrorMessage());
    }


    /**
     * @param $student StudentModel
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function updateStudent( $student ){

        //Verificar si estudiante existe
        $student_aux = $this->getStudent_ById( $student->getId() );

        //Verificamos si cambio el ID de itson
        if( $student_aux['itson_id'] != $student->getItsonId() ){
            $result = $this->isItsonIdExist( $student->getItsonId() );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException( "updateStudent","Ocurrio un error al verificar id ITSON", $result->getErrorMessage());
            else if( $result->getOperation() == true )
                throw new ConflictException( "ITSON id ya existe" );
        }

        //Verificamos si cambio carrera
        if( $student_aux['career_id'] != $student->getCareer() ){
            $careerService = new CareerService();
            $careerService->getCareer_ById( $student->getCareer() );
        }

        //Se actualizan datos de alumno
        $result = $this->perStudents->updateStudent( $student );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( "updateStudent","Ocurrio un error al actualizar estudiante", $result->getErrorMessage());
    }


    /**
     * Obtiene todas las horas y dias de un horario asi como las materias
     * @param $studentId int
     * @return array
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
            $hours_days = $scheduleService->getScheduleHours_ById_Enabled($schedule->getId());
            //Si no tiene horas, no hay problema
        }catch (InternalErrorException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
            //Si no hay horas, no hay problema
        }catch (NoContentException $e){}

        //se obtiene materias (si hay)
        $subjects = array();
        try {
            $subjects = $scheduleService->getScheduleSubjects_ById_Enabled($schedule->getId());
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

        //se envia a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->insertSchedule( $studentId );
    }


    //----------------------- ADVISORIES

    /**
     * @param $student_id int
     * @param $subject int
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws NotFoundException
     */
    public function createAdvisoryCurrentPeriod($student_id, $subject)
    {
        //Se comprueba existencia de alumno
        $this->getStudent_ById( $student_id );

        //Se obtiene periodo actual
        $advisoryService = new AdvisoryService();
        $advisoryService->insertAdvisory_CurrentPeriod( $student_id, $subject );
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

