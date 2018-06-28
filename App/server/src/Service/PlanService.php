<?php namespace App\Service;

use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;

use App\Model\DataResult;
use App\Persistence\PlansPeristence;
use App\Utils;

class PlanService{

    private $perPlans;

    public function __construct(){
        $this->perPlans = new PlansPeristence();
    }

    /**
     * Obtiene todos los planes registrados
     * @throws InternalErrorException
     * @throws NoContentException
     * @return \mysqli_result
     */
    public function getPlans(){
        $result = $this->perPlans->getPlans();

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":getPlans","Ocurrio un error al obtener planes", $result->getErrorMessage());

        else if( Utils::isEmpty($result->getOperation()) )
            throw new NoContentException("Sin planes registrados");

        else
            return $result->getData();
    }


    /**
     * Obtiene plan por ID
     * @param $planId int
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getPlan_ById( $planId ){
        $result = $this->perPlans->getPlan_ById( $planId );

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":getPlanById","Ocurrio un error al obtener plan", $result->getErrorMessage());

        else if( Utils::isEmpty($result->getOperation()) )
            throw new NotFoundException("No existe plan");

        else
            return $result->getData();
    }

    /**
     * Crea un nuevo plan
     * @param $year string
     * @throws InternalErrorException
     * @throws ConflictException
     */
    public function createPlan($year ){

        $result = $this->isPlanExist_ByYear( $year );
        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":createPlan","Error al obtener plan por año");
        else if( $result->getOperation() == true )
            throw new ConflictException("Plan ya existe");

        //Inteta registrar
        $this->perPlans->createPlan($year);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":createPlan","Error al registrar plan", $result->getErrorMessage());
    }

    /**
     * actualiza plan
     * @param $planId int
     * @param $year string
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function updatePlan($planId, $year){
        $result = $this->getPlan_ById( $planId );

        //Comprobando que no exista año
        $result = $this->isPlanExist_ByYear( $year );
        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":updatePlan","Error al obtener plan por año");
        else if( $result->getOperation() == true )
            throw new ConflictException("Plan ya existe");

        //Se actualiza plan
        $this->perPlans->updatePlan($planId, $year);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":updatePlan","Error al actualizar plan", $result->getErrorMessage());
    }

    /**
     * @param $planId int
     * @param $status
     * Cambia status de plan
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($planId, $status){

        //Verifica que plan exista
        $this->getPlan_ById( $planId );

        if( $status == Utils::$STATUS_DISABLE ){
            $result = $this->perPlans->changeStatusToDisable($planId);
            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException(static::class.":changeStatus","Error al deshabilitar plan", $result->getErrorMessage());
        }
        else if( $status == Utils::$STATUS_ENABLE ){
            $result = $this->perPlans->changeStatusToEnable($planId);
            if( Utils::isError($result->getOperation()) )
                throw new InternalErrorException(static::class.":changeStatus","Error al habilitar plan", $result->getErrorMessage());
        }
    }


    /**
     * Elimina plan
     * @param $planId int
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function deletePlan($planId){

        //Verifica que plan exista
        $this->getPlan_ById( $planId );

        //Inteta registrar
        $result = $this->perPlans->deletePlan($planId);

        if( Utils::isError($result->getOperation()) )
            throw new InternalErrorException(static::class.":deletePlan","Error al eliminar plan", $result->getErrorMessage());
    }

    /**
     * Verifica si plan existe por año
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

    /**
     * Verifica si plan existe por ID
     * @param $year
     * @return DataResult
     */    
    private function isPlanExist_ById($planId){
        $result = $this->perPlans->getPlan_ById($planId);
        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }


}


