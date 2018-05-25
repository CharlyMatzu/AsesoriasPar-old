<?php namespace App\Persistence;

use App\Utils;

class PlansPeristence extends Persistence{


    public function __construct(){}

    private $campos = "SELECT
                          plan_id,
                          year,
                          register_date
                        FROM plan ";

    public function getPlans(){
        $query = $this->campos;
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    public function getLastPlan(){
        $query = $this->campos.
                    "ORDER BY plan_id DESC LIMIT 1";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    public function getPlan_ById($planId){
        $query = $this->campos.
            "WHERE plan_id = $planId";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Obtiene el plan que conincida con el año
     * @param $year
     * @return \App\Model\DataResult
     */
    public function getPlan_ByYear( $year ){
        $query = $this->campos.
            "WHERE year = '$year'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Busca las coincidencias con uno o mas valores numericos que concuerden con el año del plan
     * @param $number
     * @return \App\Model\DataResult
     */
    public function getPlan_BySearch( $number ){
        $query = $this->campos.
            "WHERE year LIKE '%$number%'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $year string año del plan
     * @return \App\Model\DataResult
     */
    public function createPlan( $year ){
        $query = "INSERT INTO plan(year) VALUES('$year')";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $planID int
     * @param $year string
     *
     * @return \App\Model\DataResult
     */
    public function updatePlan( $planID, $year ){
        $query = "UPDATE plan SET year = '$year' WHERE plan_id = $planID";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function changeStatusToDisable($id ){
        $query = "UPDATE plan
                  SET status = ".Utils::$STATUS_DISABLE."
                  WHERE plan_id = $id";
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function changeStatusToEnable($id ){
        $query = "UPDATE plan
                  SET status = ".Utils::$STATUS_ENABLE."
                  WHERE plan_id = $id";
        return  self::executeQuery($query);
    }


    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function deletePlan($id)
    {
        $query = "DELETE FROM plan
                  WHERE plan_id = $id";
        return  self::executeQuery($query);
    }

}

