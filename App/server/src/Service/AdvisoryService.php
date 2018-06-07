<?php namespace App\Service;


use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;
use App\Utils;
use Carbon\Carbon;

use App\Persistence\AdvisoriesPersistence;
use App\Model\AdvisoryModel;

class AdvisoryService
{

    private $perAsesorias;

    public function __construct(){
        $this->perAsesorias = new AdvisoriesPersistence();
    }


    /**
     * @throws InternalErrorException
     * @throws \App\Exceptions\NoContentException
     * @return \mysqli_result
     */
    public function getCurrentAdvisories()
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        $result = $this->perAsesorias->getAdvisories_ByPeriod( $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":getCurrentAdvisories",
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

        $result = $this->perAsesorias->getAdvisories_ByStuden_ByPeriod( $student_id, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":getCurrentAdvisory_ByStudent",
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
    public function getCurrentAdvisories_Requested($student_id )
    {
        $periodService = new PeriodService();
        $period = $periodService->getCurrentPeriod();

        $result = $this->perAsesorias->getRequestedAdvisories_ByStuden_ByPeriod( $student_id, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":getCurrentAdvisories_Requested",
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

        $result = $this->perAsesorias->getAdviserAdvisories_ByStuden_ByPeriod( $student_id, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":getCurrentAdvisories_Adviser",
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
        $result = $this->perAsesorias->getAdvisory_ById($id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":getAdvisory_ById",
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
    public function getAdvisoryHours_ById($id){
        $result = $this->perAsesorias->getAdvisoryHours_ById($id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":getAdvisoryHours_ById",
                "Error al obtener horas de asesoria", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe asesorias");

        return $result->getData();

    }


    /**
     * @param $advisory AdvisoryModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws NotFoundException
     */
    public function insertAdvisory_CurrentPeriod($advisory)
    {
        //se obtiene periodo actual
        $periodServ = new PeriodService();
        $period = $periodServ->getCurrentPeriod();

        $student_id = $advisory->getStudent();
        $subject_id = $advisory->getSubject();

        //TODO: no debe estar empalmada con otra asesoria a la misma hora/dia (activa: status 2)


        //Se buscan asesorias activas en el mismo periodo que tengan la misma materia del mismo asesor
        $advisories = $this->perAsesorias->getAdvisories_ByStudent_BySubject_ByPeriod($student_id, $subject_id, $period['id']);
        if( Utils::isError( $advisories->getOperation() ) )
            throw new InternalErrorException(static::class.":insertAdvisory_CurrentPeriod",
                "Error al obtener asesorias", $advisories->getErrorMessage());

        $this->checkAdvisoryRedundancy( $advisories->getData() );

        //Se verifica que materia exista
        $subjectServ = new SubjectService();
        $subjectServ->getSubject_ById( $subject_id );

        //Se registra asesorias
        $result = $this->perAsesorias->insertAdvisory( $advisory, $period['id'] );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException(static::class.":insertAdvisory",
                "Error al registrar asesorias", $advisories->getErrorMessage());
    }


    /**
     * @param $advisories array|\mysqli_result
     * @return void
     * @throws ConflictException
     */
    private function checkAdvisoryRedundancy($advisories ){

        //Si esta vacio, no es redundante
        if( empty($advisories) )
            return;

        foreach ( $advisories as $ad ){
            //Si se encuentra una asesorias activa de la misma materia
            // en estado activa o pendiente, entonces es redundante
            if( $ad['status'] == Utils::$STATUS_ACTIVE )
                throw new ConflictException("Ya existe asesorias con dicha materia activa");
            else if( $ad['status'] == Utils::$STATUS_PENDING )
                throw new ConflictException("Ya existe asesorias con dicha materia pendiente");
        }
    }




//
//    /**
//     * @param $idStudent
//     * @return array|null|string
//     */
//    public function getCurrentAsesoriasLikeAsesor_ByStudent($idStudent ){
//        $conHorarios = new ScheduleControl();
//        $cycle = $conHorarios->getCurrentPeriod();
//        if( !is_array($cycle) )
//            return $cycle;
//        else{
//            $result = $this->perAsesorias->getAsesoriasLikeAsesor_ByStudentIdAndSchedule( $idStudent, $cycle['id'] );
//            if( $result === false )
//                return 'error';
//            else if( $result === null )
//                return null;
//            else{
//                $array = array();
//                foreach( $result as $as ){
//                    $array[] = self::makeObject_Asesoria( $as );
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