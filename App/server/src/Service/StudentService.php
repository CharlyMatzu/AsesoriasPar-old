<?php namespace App\Service;

use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;

use App\Exceptions\RequestException;
use App\Model\Schedule;
use App\Persistence\StudentsPersistence;
use App\Model\Student;
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
            throw new InternalErrorException( static::class.":getStudent_ById","Ocurrio un error al obtener estudiante", $result->getErrorMessage());
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
            throw new InternalErrorException(static::class.":getStudents","Ocurrio un error al obtener usuarios", $result->getErrorMessage());
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
            throw new InternalErrorException(static::class.":searchStudents_ByData","Ocurrio un error al obtener usuarios", $result->getErrorMessage());
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
     * @param $student Student
     *
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertStudent( $student ){

        //Verifica que id de itson no exista
        $result = $this->isItsonIdExist( $student->getItsonId() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class.":insertStudents","Ocurrio un error al verificar id ITSON", $result->getErrorMessage());
        else if( $result->getOperation() == true )
            throw new ConflictException( "ITSON id ya existe" );

        //Se registra usuario
        $result = $this->perStudents->insertStudent( $student );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException( static::class."insertStudents","Ocurrio un error al registrar usuario", $result->getErrorMessage());
    }


    /**
     * @param $student Student
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
                throw new InternalErrorException( static::class.":updateStudent","Ocurrio un error al verificar id ITSON", $result->getErrorMessage());
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
            throw new InternalErrorException( static::class.":updateStudent","Ocurrio un error al actualizar estudiante", $result->getErrorMessage());
    }


    /**
     * Obtiene todas las horas y dias de un horario asi como las materias
     * @param $studentId int
     * @return array
     * @throws RequestException
     */
    public function getCurrentSchedule($studentId)
    {
        //TODO: mover todo a un método en ScheduleService

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
            $schedule = ScheduleService::makeScheduleModel($schedule);
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
            "status" => $schedule->getStatus(),
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
     * @param $studentId
     * @param $schedule_subjects
     *
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function addScheduleSubjects_current($studentId, $schedule_subjects )
    {
        //Se comprueba existencia de horario
        $schedule = $this->getCurrentSchedule( $studentId );

        //se envia a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->insertScheduleSubjects($schedule['id'], $schedule_subjects );
    }


    /**
     * @param $studentId int
     * @param $scheduleId int
     * @param $hours array
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function updateScheduleHours($studentId, $scheduleId, $hours)
    {
        $this->getStudent_ById($studentId);

        //se envia a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->updateScheduleHours( $scheduleId, $hours );
    }


    /**
     * @param $studentId int
     * @param $scheduleId int
     * @param $subjects array
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function updateScheduleSubjects($studentId, $scheduleId, $subjects)
    {
        $this->getStudent_ById($studentId);

        //se envia a registrar horario
        $scheduleService = new ScheduleService();
        $scheduleService->updateScheduleSubjects( $scheduleId, $subjects );
    }





    public static function makeStudentModel( $data ){
        $student = new Student();
        //setting data
        $student->setId( $data['id'] );
        $student->setItsonId( $data['itson_id'] );
        $student->setFirstName( $data['first_name'] );
        $student->setLastName( $data['last_name'] );
        $student->setPhone( $data['phone'] );
        $student->setFacebook( $data['facebook'] );
        $student->setAvatar( $data['avatar'] );
        $student->setRegisterDate( $data['register_date'] );

//        $student->setStatus( $data['status'] );
        $student->setUser( $data['user_id'] );
        $student->setCareer( $data['career_id'] );

        //Returning object
        return $student;
    }




}

