<?php namespace Service;

use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Model\DataResult;
use Persistence\PlansPeristence;
use Utils;

class PlanService{

    private $perPlans;

    public function __construct(){
        $this->perPlans = new PlansPeristence();
    }

    /**
     * @throws InternalErrorException
     * @throws NoContentException
     * @return \mysqli_result
     */
    public function getPlans(){
        $result = $this->perPlans->getPlans();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener planes", $result->getErrorMessage());

        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("Sin planes registrados");

        else
            return $result->getData();
    }


    /**
     * @param $planId int
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getPlan_ById( $planId ){
        $result = $this->perPlans->getPlan_ById( $planId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Ocurrio un error al obtener plan", $result->getErrorMessage());

        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe plan");

        else
            return $result->getData();
    }

    /**
     * @param $year string
     * @throws InternalErrorException
     * @throws ConflictException
     */
    public function createPlan($year ){

        $result = $this->isPlanExist_ByYear( $year );
        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por año");
        else if( $result->getOperation() == true )
            throw new ConflictException("Plan ya existe");

        //Inteta registrar
        $this->perPlans->createPlan($year);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al registrar plan", $result->getErrorMessage());
    }

    /**
     * @param $planId int
     * @param $year string
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function updatePlan($planId, $year){
        $result = $this->isPlanExist_ById( $planId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por ID", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe plan");

        //Comprobando que no exista año
        $result = $this->isPlanExist_ByYear( $year );
        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por año");
        else if( $result->getOperation() == true )
            throw new ConflictException("Plan ya existe");

        //Se actualiza plan
        $this->perPlans->updatePlan($planId, $year);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al actualizar plan", $result->getErrorMessage());
    }

    /**
     * @param $planId int
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function disablePlan($planId){
        $result = $this->isPlanExist_ById( $planId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por ID", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe plan");

        //Inteta registrar
        $this->perPlans->changeStatusToDeleted($planId);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al deshabilitar plan", $result->getErrorMessage());
    }


    /**
     * @param $planId int
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function deletePlan($planId){
        $result = $this->isPlanExist_ById( $planId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por ID", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe plan");

        //Inteta registrar
        $this->perPlans->deletePlan($planId);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al eliminar plan", $result->getErrorMessage());
    }


    /**
     * @param $year
     * @return DataResult
     */
    private function isPlanExist_ByYear($year){

        $result = $this->perPlans->getPlan_ByYear($year);
        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }

    private function isPlanExist_ById($planId){
        $result = $this->perPlans->getPlan_ById($planId);
        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }


}


