<?php namespace App\Service;


use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NoContentException;
use App\Exceptions\Request\NotFoundException;
use App\Persistence\PeriodsPersistence;
use App\Model\PeriodModel;
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
            throw new InternalErrorException("getPeriods","Ocurrió un error al obtener periodos", $result->getErrorMessage());
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
            throw new InternalErrorException("getPeriodById","Ocurrió un error al obtener periodo", $result->getErrorMessage());
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
            throw new InternalErrorException("getPeriodByRange","Ocurrió un error al obtener periodo", $result->getErrorMessage());
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
            throw new InternalErrorException("getCurrentPeriod","Error al obtener periodo actual", $result->getErrorMessage());
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
            throw new InternalErrorException("createPeriod",
                "Ocurrió un error al comprobar periodo entre fechas", $result->getErrorMessage());

        else if( $result->getOperation() == true )
            throw new ConflictException("Periodo se empalma con otro");

        //------------FECHA DE TERMINO ES MENOR O IGUAL A INICIO
        $dateStart = new DateTime( $start );
        $dateEnd = new DateTime( $end );

        //FIXME: envitar que la fecha de termino sea antes o igual a la de inicio
        //TODO: debe validar que fecha de cierre no se empalme con otra o hacer que la ultima fecha de cierre(+ un dia) sea la valida en adelante
        if( $dateEnd <= $dateStart )
            throw new ConflictException("Fecha de cierra incorrecta debe ser posterior a la de inicio");


        //------------REISTRANDO PERIODO
        $result = $this->perPeriods->insertPeriod( $start, $end );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("createPeriod",
                "Ocurrió un error al registrar periodo", $result->getErrorMessage());
    }

//   ------------------------------------- UPDATE CYCLES

    /**
     * @param $period PeriodModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function updatePeriod( $period ){

        //Comprobante existencia
        $this->getPeriod_ById( $period->getId() );

        //TODO: comprobar que las fechas sean correctas (empalmadas, inicio antes de fin, formatos)
        if( $period->getDateEnd() <= $period->getDateStart() )
            throw new ConflictException("Fecha de cierra incorrecta, debe ser posterior a la de inicio");

        //Se actualiza
        $result = $this->perPeriods->updatePeriod( $period );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("updatePeriod","Error al actualizar periodo", $result->getErrorMessage());

    }

    //   ------------------------------------- DELETE CYCLES


    /**
     * @param $period_Id
     * @param $status
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($period_Id, $status ){

        $this->getPeriod_ById($period_Id);

        $result = $this->perPeriods->changeStatus( $period_Id, $status );
        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("changeStatus","Error al cambiar estado de periodo", $result->getErrorMessage());
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
            throw new InternalErrorException("deletePeriod","Error al comprobar existencia de periodo", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("Periodo no existe");

        //Se elimina
        $result = $this->perPeriods->deletePeriod( $id );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("deletePeriod","No se pudo eliminar periodo", $result->getErrorMessage());
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


