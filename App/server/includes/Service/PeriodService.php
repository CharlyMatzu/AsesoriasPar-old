<?php namespace Service;

use Exceptions\BadRequestException;
use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Persistence\Periods;
use Objects\Period;
use DateTime;
use Utils;

class PeriodService{

    private $perPeriods;

    public function __construct(){
        $this->perPeriods = new Periods();
    }

    /**
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getPeriods(){
        $result = $this->perPeriods->getPeriods();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener periodos", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontraron periodos reistrados");
        else
            return $result->getData();
    }

    /**
     * @param $periodId
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getPeriod_ById( $periodId ){
        $result = $this->perPeriods->getPeriod_ById( $periodId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener periodo");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe periodo");
        else
            return $result->getData();
    }

    /**
     * @param $date_start
     * @param $date_end
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getPeriods_ByDateRange($date_start, $date_end ){
        $result = $this->perPeriods->getPeriods_Range( $date_start, $date_end );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener periodo");
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontraron periodos reistrados");
        else
            return $result->getData();
    }




//    public function getPeriod_ByScheduleId( $schedule ){
//        $result = $this->perPeriods->getPeriod_ByScheduleId( $schedule );
//        if( $result === Utils::$ERROR_RESULT )
//            return 'error';
//        else if( $result === null )
//            return null;
//        else{
//            $arrayPeriod = array();
//            foreach( $result as $per ) {
//                //Se agrega al array
//                $arrayPeriod[] = $this->makeObject_period($per);
//            }
//            return $arrayPeriod;
//        }
//    }

    //TODO: verificar el formato de la fecha
    //TODO: verificar que no sea antes de NOW
    /**
     * @param $start
     * @param $end
     * @return array
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function registerPeriod($start, $end ){

        //------------FECHAS EMPALMADAS
        $result = $this->isPeriodBetweenOther( $start );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al comprobar periodo entre fechas", $result->getErrorMessage());

        else if( $result->getOperation() == true )
            throw new ConflictException("Periodo se empalma con otro");

        //------------FECHA DE TERMINO ES MENOR O IGUAL A INICIO
        $dateStart = new DateTime( $start );
        $dateEnd = new DateTime( $end );

        //TODO: envitar que la fecha de termino sea antes o igual a la de inicio
        if( $dateEnd <= $dateStart )
            throw new ConflictException("Fecha de cierra incorrecta debe ser posterior a la de inicio");


        //------------REISTRANDO PERIODO
        $result = $this->perPeriods->insertPeriod( $start, $end );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al registrar periodo", $result->getErrorMessage());
        else
            return Utils::makeArrayResponse(
                "Se registro periodo con éxito",
                $start.' a '.$end
            );



    }

//   ------------------------------------- UPDATE CYCLES

    /**
     * @param $period Period
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function updatePeriod( $period ){
        //TODO: comprobar que las fechas sean correctas (empalmadas, inicio antes de fin, formatos)

        $result = $this->isPeriodExist_ById( $period->getId() );
        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("No se pudo comprobar existencia de periodo");
        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe periodo");

        //Se actualiza
        $result = $this->perPeriods->updatePeriod( $period );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("No se pudo actualizar periodo");
        else
            return Utils::makeArrayResponse(
                "Periodo actualizado con exito"
            );

    }

    //   ------------------------------------- DELETE CYCLES


    /**
     * @param $periodId
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function disablePeriod($periodId ){

        $result = $this->isPeriodExist_ById($periodId);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al comprobar existencia de periodo", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("Periodo no existe");

        //Se elimina
        $result = $this->perPeriods->changeStatusToDelete( $periodId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("No se pudo deshabilitar periodo");
        else
            return Utils::makeArrayResponse(
                "Se deshabilito periodo con exito",
                $periodId
            );
    }


    //--------------------------
    //  FUNCIONES
    //--------------------------


    /**
     * @param $periodId
     * @return \Objects\DataResult
     */
    public function isPeriodExist_ById( $periodId ){
        $result = $this->perPeriods->getPeriod_ById( $periodId );
        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }


    /**
     * @param $date
     * @return \Objects\DataResult
     */
    public function isPeriodBetweenOther( $date ){
        $result = $this->perPeriods->getPeriodWhereIsBetween( $date );

        if( Utils::isSuccessWithResult($result->getOperation()) )
            $result->setOperation(true);
        else if( Utils::isEmpty($result->getOperation()) )
            $result->setOperation(false);

        return $result;
    }

}


