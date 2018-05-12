<?php namespace Control;

use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Objects\DataResult;
use Persistence\Plans;
use Utils;

class PlanControl{

    private $perPlans;

    public function __construct(){
        $this->perPlans = new Plans();
    }

    /**
     * @return array|null|string
     * @throws InternalErrorException
     * @throws NoContentException
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
            throw new InternalErrorException("Ocurrio un error al obtener plan por Id", $result->getErrorMessage());

        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe plan");

        else
            return $result->getData();
    }

    /**
     * @param $year string
     * @return array
     * @throws InternalErrorException
     * @throws ConflictException
     */
    public function registerPlan($year ){

        $result = $this->isPlanExist_ByYear( $year );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por año", $result->getErrorMessage());

        else if( $result->getOperation() == true )
            throw new ConflictException("Plan ya existe");

        //Inteta registrar
        $this->perPlans->createPlan($year);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al registrar plan", $result->getErrorMessage());
        else
            return Utils::makeArrayResponse(
                "Plan registrado con exito",
                $year
            );
    }

    /**
     * @param $planId int
     * @param $year string
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function updatePlan($planId, $year){
        $result = $this->isPlanExist_ById( $planId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al obtener plan por ID", $result->getErrorMessage());

        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe plan");

        //Inteta registrar
        $this->perPlans->updatePlan($planId, $year);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException("Error al actualizar plan", $result->getErrorMessage());
        else
            return Utils::makeArrayResponse(
                "Plan actualizado con exito",
                $planId.", ".$year
            );
    }

    /**
     * @param $planId int
     * @return array
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
        else
            return Utils::makeArrayResponse(
                "Plan deshabilitado con exito",
                $planId
            );
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


