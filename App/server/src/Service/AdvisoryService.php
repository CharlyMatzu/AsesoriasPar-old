<?php namespace App\Service;



use App\Exceptions\Persistence\TransactionException;
use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NoContentException;
use App\Exceptions\Request\NotFoundException;
use App\Exceptions\Request\RequestException;
use App\Model\MailModel;
use App\Persistence\PlansPeristence;
use App\Utils;

use App\Persistence\AdvisoriesPersistence;
use App\Model\AdvisoryModel;

class AdvisoryService
{

    private $adviPer;

    public function __construct(){
        $this->adviPer = new AdvisoriesPersistence();
    }


    /**
     * @return \mysqli_result|null
     * @throws \App\Exceptions\Request\InternalErrorException
     * @throws \App\Exceptions\Request\NoContentException
     */
    public function getCurrentAdvisories()
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        $result = $this->adviPer->getAdvisories_ByPeriod( $period['id'] );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentAdvisories",
                "Error al obtener asesorias en periodo actual", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }

    /**
     * @param $student_id int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentAdvisories_ByStudent($student_id)
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        $result = $this->adviPer->getStudentAdvisories_ByPeriod( $student_id, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentAdvisory_ByStudent",
                "Error al obtener asesorias de estudiante", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }


    /**
     * @param $period_id
     * @param $subject_id
     * @param $student_id
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentAdvisers_BySubject_IgnoreStudent($period_id, $subject_id, $student_id){

        $result = $this->adviPer->getAdvisers_ByPeriod_BySubject_IngoreStudent( $period_id, $subject_id, $student_id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentAdvisers_BySubject_IgnoreStudent",
                "Error al obtener asesores disponibles", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }


    /**
     * @param $student_id int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentAdvisories_Requested($student_id )
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        $result = $this->adviPer->getRequestedAdvisories_ByStuden_ByPeriod( $student_id, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentAdvisories_Requested",
                "Error al obtener asesorias de estudiante", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }

    /**
     * @param $student_id int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentAdvisories_Adviser($student_id )
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        $result = $this->adviPer->getAdviserAdvisories_ByStuden_ByPeriod( $student_id, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCurrentAdvisories_Adviser",
                "Error al obtener asesorias de estudiante", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }



    /**
     * @param $id int
     *
     * @return mixed
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getAdvisory_ById($id){
        $result = $this->adviPer->getAdvisory_ById($id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getAdvisory_ById",
                "Error al obtener asesoria", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe asesoria");

        return $result->getData()[0];
    }


    /**
     * @param $id int
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getAdvisorySchedule_ById($id){
        $result = $this->adviPer->getAdvisoryHours_ById($id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getAdvisorySchedule_ById",
                "Error al obtener horas de asesoria", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe asesorias");

        return $result->getData();

    }


    /**
     * @param $advisory AdvisoryModel
     *
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws ConflictException
     * @throws NotFoundException
     */
    public function insertAdvisory_CurrentPeriod( $advisory )
    {
        //se obtiene periodo actual
        $periodServ = new PeriodService();
        //TODO: periodo debe estar habilitado
        $period = $periodServ->getCurrentPeriod();

        $student_id = $advisory->getStudent();
        $subject_id = $advisory->getSubject();

        //Se verifica que materia exista
        $subjectServ = new SubjectService();
        $req_subject = $subjectServ->getSubject_ById( $subject_id );

        //TODO: no debe estar empalmada con otra asesoria a la misma hora/dia (activa: status 2)
        //TODO: no debe permitir solicitar si es asesor

        //Se buscan asesorías activas en el mismo periodo que tengan la misma materia del mismo asesor
        $advisories = $this->adviPer->getStudentAdvisories_BySubject_ByPeriod($student_id, $subject_id, $period['id']);
        if( Utils::isError( $advisories->getOperation() ) )
            throw new InternalErrorException("insertAdvisory_CurrentPeriod",
                "Error al obtener asesorias", $advisories->getErrorMessage());

        //Verifica que no exista una asesoría similar activa
        $this->checkAdvisoryRedundancy( $advisories->getData(), $advisory );

        //---Verifica que no este solicitando asesorías de la que es asesor
        //Obtiene horario de asesor
        $scheServ = new ScheduleService();
        $schedule = $scheServ->getCurrentSchedule_ByStudentId( $student_id );
        //Obtiene materias de asesor
        $subjectsArray = $scheServ->getScheduleSubjects_ById_Enabled( $schedule['id'] );

        //Se compara materia solicitada con materias de horario
        if( !empty($subjectsArray) ){
            //Recorre materias de horario
            foreach ( $subjectsArray as $sub ){
                //Si es la misma materia
                if( $req_subject['id'] === $sub['subject_id'] ){
                    //solo si esta deshabilitada o bloqueada, se puede solicitar, caso contrario, excepción
                    if( $sub['status'] === Utils::$STATUS_VALIDATED || $sub['status'] === Utils::$STATUS_PENDING )
                        throw new ConflictException("No puedes solicitar asesoría de materia de la que eres asesor");
                    //Si no cumple, se termina y continua
                    else
                        break;
                }
            }
        }

        //Se registra asesorías
        $result = $this->adviPer->insertAdvisory( $advisory, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("insertAdvisory",
                "Error al registrar asesorias", $advisories->getErrorMessage());


        //envía correo de confirmación
        try{

            $userServ = new UserService();
            $stuServ = new StudentService();
            $student = $stuServ->getStudent_ById( $advisory->getStudent() );
            $name = $student['first_name']." ".$student['last_name'];

            $subject = $subjectServ->getSubject_ById( $advisory->getSubject() );

            $mailServ = new MailService();
            $mailServ->sendEmailToStaff(
                "Nueva asesoria",
                "Se ha registrado una nueva asesoria por: <strong>$name</strong>, para la materia de <strong>".$subject['name']."</strong>",
                $userServ->getStaffUsers());

        }catch (RequestException $e){}
    }


    /**
     * Verifica que no se pueda solicitar una asesoría para la misma materia si ya hay una solicitud en proceso o activa,
     * de igual manera, verifica que no se pueda solicitar de una materia de la que es asesor
     *
     * @param $advisories array|\mysqli_result
     * @param $advisory AdvisoryModel
     *
     * @return void
     * @throws ConflictException
     * @throws InternalErrorException
     */
    private function checkAdvisoryRedundancy( $advisories, $advisory ){

        //Si esta vacío, no es redundante
        if( empty( $advisories ) )
            return;

        foreach ( $advisories as $ad ){
            //Si se encuentra una asesorias activa de la misma materia
            // en estado activa o pendiente, entonces es redundante
            if( $ad['status'] == Utils::$STATUS_ACTIVE )
                throw new ConflictException("Ya existe asesorias activa con dicha materia");
            else if( $ad['status'] == Utils::$STATUS_PENDING )
                throw new ConflictException("Ya existe asesorias pendiente con dicha materia");
        }


        try{
            //Se obtiene horario actual de alumno
            $scheServ = new ScheduleService();
            $schedule = $scheServ->getCurrentSchedule_ByStudentId( $advisory->getStudent() );

            //verificar que no pueda solicitar asesoría de materia de la que es asesor (validada)
            $subject = $scheServ->getScheduleSubject_BySubject( $advisory->getSubject(), $schedule['id'] );
            if( $subject['status'] === Utils::$STATUS_VALIDATED )
                throw new ConflictException("No se puede solicitar asesoría de una materia de la que es asesor");

            //Si no se encontró, no hay problema
        }
        catch ( NotFoundException $e){}
        catch ( NoContentException $e){}

    }

    /**
     * @param $advisory_id
     * @param $adviser_id
     * @param $hours
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function assignAdviser($advisory_id, $adviser_id, $hours){

        //asesoría
        $advisory = $this->getAdvisory_ById( $advisory_id );

        //Estudiantes
        $studentServ = new StudentService();
        $adviser = $studentServ->getStudent_ById( $adviser_id );
        $alumn = $studentServ->getStudent_ById( $advisory['alumn_id'] );

        if( $adviser['id'] == $alumn['id'] )
            throw new ConflictException("No se puede asignar estudiante a sí mismo");

        //----Inicia transacción
        try {
            PlansPeristence::initTransaction();
        } catch (TransactionException $e) {
            throw new InternalErrorException("assignAdviser", $e->getMessage());
        }


        //Actualiza datos de asesoría
        $result = $this->adviPer->assignAdviser( $advisory_id, $adviser_id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("assignAdviser",
                "Error al actualizar asesoria", $result->getErrorMessage());

        //Agrega horario
        //TODO verificar que horas esten activas
        foreach ( $hours as $h ){
            $result = $this->adviPer->insertAdvisoryHours( $advisory_id, $h );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("assignAdviser",
                    "Error al registrar horario", $result->getErrorMessage());
        }

        //Se guarda registro
        try {
            PlansPeristence::commitTransaction();
        } catch (TransactionException $e) {
            throw new InternalErrorException("assignAdviser", $e->getMessage());
        }

        //Envío de correo
        try{
            $userServ =  new UserService();
            $adviserUser = $userServ->getUser_ByStudentId( $adviser['id'] );
            $alumnUser = $userServ->getUser_ByStudentId( $alumn['id'] );

            //Obteniendo materia
            $subServ = new SubjectService();
            $subject = $subServ->getSubject_ById( $advisory['subject_id'] );
            $mailServ = new MailService();

            //Correo para Asesor
            $mail = new MailModel();
            $mail->addAdress( $adviserUser['email'] );
            $mail->setSubject("Nuevo asesorado");
            $mail->setBody("Has sido asignado como asesor al alumno <strong>".$alumn['first_name']." ".$alumn['last_name']."</strong> para la materia de: <strong>".$subject['name']."</strong>");
            $mail->setPlainBody("Has sido asignado como asesor al alumno ".$alumn['first_name']." ".$alumn['last_name']."para la materia de: ".$subject['name']);

            $mailServ->sendMail($mail);

            //Correo para Alumno
            $mail = new MailModel();
            $mail->addAdress( $adviserUser['email'] );
            $mail->setSubject("Asesor asignado");
            $mail->setBody("Se te ha sido asignado asesor el alumno <strong>".$adviser['first_name']." ".$adviser['last_name']."</strong> para la materia de: <strong>".$subject['name']."</strong>");
            $mail->setPlainBody("Se te ha sido asignado asesor el alumno ".$adviser['first_name']." ".$adviser['last_name']."para la materia de: ".$subject['name']);

        }catch (RequestException $e){}

    }

    /**
     * @param $advisory_id
     *
     * @return bool
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function finalizeAdvisory($advisory_id){
        $result = $this->getAdvisory_ById( $advisory_id );

        //verificar si ya fue finalizada anteriormente
        if( $result['status'] === Utils::$STATUS_FINALIZED )
            return true;

        $result = $this->adviPer->finalizeAdvisory($advisory_id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("finalizeAdvisory",
                "Error al finalizar asesoria", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe asesoria");

        return true;
        //TODO: enviar correo a asociados
    }

    /**
     * @param $advisory_id int
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function getAdvisorySchedule($advisory_id){
        $this->getAdvisory_ById( $advisory_id );

        $result = $this->adviPer->getAdvisoryHours_ById($advisory_id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getAdvisorySchedule",
                "Error al obtener horario de asesoria", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }


//
//    /**
//     * @param $idStudent
//     * @return array|null|string
//     */
//    public function getCurrentasesoriasLikeAsesor_ByStudent($idStudent ){
//        $conHorarios = new ScheduleControl();
//        $cycle = $conHorarios->getCurrentPeriod();
//        if( !is_array($cycle) )
//            return $cycle;
//        else{
//            $result = $this->perasesorias->getasesoriasLikeAsesor_ByStudentIdAndSchedule( $idStudent, $cycle['id'] );
//            if( $result === false )
//                return 'error';
//            else if( $result === null )
//                return null;
//            else{
//                $array = array();
//                foreach( $result as $as ){
//                    $array[] = self::makeObject_asesoria( $as );
//                }
//                return $array;
//            }
//        }
//
//    }
//
//
//    //-----------------
//    // SubjectsPersistence
//    //-----------------
//
//    public function getCurrentAvailableSubject( $idStudent ){
//        $conSubjects = new SubjectControl();
//        return $conSubjects->getCurrAvailScheduleSubs_SkipSutdent( $idStudent );
//    }
//
//
//
//    //------------------
//    //  Fechas
//    //------------------
//
//    //http://php.net/manual/es/function.date.php
//    /**
//     * Método que compara la diferencia entre dos fechas y regresa la diferencia.
//     * si el valor de positivo es true regresará valores absolutos (sin signo).
//     * Por defecto siempre es false
//     *
//     * @param $fecha
//     * @param bool $positivo idicador de valor absoluto
//     *
//     * @return mixed
//     */
//    private function diferenciaDias_Hoy($fecha, $positivo = false ){
//        $hoy = Carbon::today();
//        $fechaX = Carbon::parse( $fecha );
//        $dif = $hoy->diffInDays( $fechaX, $positivo );
//        return $dif;
//    }
//
//    public function diferenciaDias( $fechaX ){
//        return $this->diferenciaDias_Hoy( $fechaX, true );
//    }
//
//    public function isAntes( $fechaX ){
//        if( $this->diferenciaDias_Hoy( $fechaX ) > 0 )
//            return true;
//        else
//            return false;
//    }
//
//    public function isHoy( $fechaX ){
//        if( $this->diferenciaDias_Hoy( $fechaX ) == 0 )
//            return true;
//        else
//            return false;
//    }
//
//
//
//    public function isPosterior( $fechaX ){
//        if( $this->diferenciaDias_Hoy( $fechaX ) < 0 )
//            return true;
//        else
//            return false;
//    }


}