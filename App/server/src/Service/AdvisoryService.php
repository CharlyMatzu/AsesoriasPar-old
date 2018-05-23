<?php namespace App\Service;


use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
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
            throw new InternalErrorException(static::class."getAdvisories_CurrentPeriod",
                "Error al obtener asesorias en periodo actual", $result->getErrorMessage());
        else if( Utils::isError( $result->getOperation() ) )
            throw new NoContentException();

        return $result->getData();
    }

    public function getAdvisory_ById($id){
        $advisory = $this->perAsesorias->getAdvisory_ById($id);
    }

    public function getAdvisoryHours_ById($id){
        $hours = $this->perAsesorias->getAdvisoryHours_ById($id);
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