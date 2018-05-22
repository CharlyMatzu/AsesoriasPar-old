<?php namespace App\Service;

use App\Exceptions\BadRequestException;
use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;
use App\Persistence\PeriodsPersistence;
use App\Model\Period;
use DateTime;
use App\Utils;

class PeriodService{

    private $perPeriods;

    public function __construct(){
        $this->perPeriods = new PeriodsPersistence();
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
            throw new InternalErrorException("Ocurrio un error al obtener periodo", $result->getErrorMessage());
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
            throw new InternalErrorException("Ocurrio un error al obtener periodo", $result->getErrorMessage());
        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("No se encontraron periodos reistrados");
        else
            return $result->getData();
    }


    /**
     * @return array
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCurrentPeriod()
    {
        $result = $this->perPeriods->getCurrentPeriod();
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al obtener periodo actual", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No hay un periodo actual registrado");

        return $result->getData()[0];
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

    /**
     * @param $start
     * @param $end
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function createPeriod($start, $end ){

        //TODO: cuando se cree un periodo, se les avisa a los estudiantes

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
    }

//   ------------------------------------- UPDATE CYCLES

    /**
     * @param $period Period
     *
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function updatePeriod( $period ){
        //TODO: comprobar que las fechas sean correctas (empalmadas, inicio antes de fin, formatos)

        try{
            $this->getPeriod_ById( $period->getId() );
        }catch (RequestException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //Se actualiza
        $result = $this->perPeriods->updatePeriod( $period );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al actualizar periodo", $result->getErrorMessage());

    }

    //   ------------------------------------- DELETE CYCLES


    /**
     * @param $periodId
     * @param $status
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($periodId, $status ){

        $result = $this->isPeriodExist_ById($periodId);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al comprobar existencia de periodo", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("Periodo no existe");

        if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->perPeriods->changeStatusToDelete( $periodId );
            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException("No se pudo deshabilitar periodo", $result->getErrorMessage());
        }
        else if( $status == Utils::$STATUS_ENABLE ){
            $result = $this->perPeriods->changeStatusToEnable( $periodId );
            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException("No se pudo habilitar periodo", $result->getErrorMessage());
        }
    }

    /**
     * @param $id
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function deletePeriod($id)
    {
        $result = $this->isPeriodExist_ById($id);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al comprobar existencia de periodo", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("Periodo no existe");

        //Se elimina
        $result = $this->perPeriods->deletePeriod( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("No se pudo eliminar periodo", $result->getErrorMessage());
    }


    /**
     * @param $periodId
     * @return \App\Model\DataResult
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
     * @return \App\Model\DataResult
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


