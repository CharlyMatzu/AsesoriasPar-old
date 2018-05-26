<?php namespace App\Persistence;


use App\Model\Period;
use App\Utils;

class PeriodsPersistence extends Persistence{


    public function __construct(){}

    private $campos = "SELECT
                          period_id as 'id',
                          date_start as 'start',
	                      date_end as 'end',
	                      date_register,
	                      status  as 'status'
                        FROM period ";

    //TODO: Los metodos que obtiene deben tomar en cuenta el status

    /**
     * @return \App\Model\DataResult
     */
    public function getPeriods(){
        $query = $this->campos;
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     */
    public function getCurrentPeriod(){
        $query = $this->campos.
                    "WHERE DATE(NOW()) BETWEEN date_start AND date_end";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function getPeriod_ById($id){
        $query = $this->campos."
                     WHERE period_id = $id";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $start
     * @param $end
     * @return \App\Model\DataResult
     */
    public function getPeriods_Range( $start, $end ){
        $query = $this->campos."
                     WHERE (date_start BETWEEN '$start' AND '$end')
                    OR (date_end BETWEEN '$start' AND '$end')";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $date
     * @return \App\Model\DataResult
     */
    public function getPeriod_ByDate($date){
        $query = $this->campos."
                     WHERE date_start = '$date' OR date_end = '$date'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     */
    public function getLastPeriod(){
        $query = $this->campos."
                     ORDER BY period_id DESC LIMIT 1";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $date string fecha correspondiente para comparar
     * @return \App\Model\DataResult
     * /TODO: debe ser capaz de saber si se empalma con diferentes fechas y no solo la ultima
     */
    public function getPeriodWhereIsBetween( $date ){
        $query = "SELECT * FROM period 
                  WHERE '$date' <= (
                      -- Obtiene la fecha de cierra del ultimo periodo
                      SELECT 
                        date_end 
                        FROM period 
                        ORDER BY period_id DESC LIMIT 1
	              );";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function getPeriod_ByScheduleId( $id ){
        $query = $this->campos." 
                        INNER JOIN schedule h ON h.fk_period = c.period_id
                        WHERE h.period_id = $id";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * @param $start string fecha de inicio
     * @param $end string fecha de termino
     * @return \App\Model\DataResult
     */
    public function insertPeriod( $start, $end ){
        $query = "INSERT INTO period (date_start, date_end) 
                  VALUES('$start', '$end')";
        return  self::executeQuery($query);
    }

    /**
     * @param $period Period
     * @return \App\Model\DataResult
     */
    public function updatePeriod( $period ){
        $query = "UPDATE period c
                  SET c.date_start =  '".$period->getDateStart()."' , c.date_end = '".$period->getDateEnd()."'
                  WHERE c.period_id = ".$period->getId();
        return  self::executeQuery($query);
    }

    /**
     * cambia el estado a 0 indicando que esta eliminado o deshabilitado
     * @param $id int
     * @return \App\Model\DataResult
     */
    public function changeStatusToDelete( $id ){
        $query = "UPDATE Period
                  SET status = ".Utils::$STATUS_DISABLE.",
                  WHERE period_id = $id";
        return  self::executeQuery($query);
    }

    /**
     * cambia el estado a 2 indicando que esta habilitado
     * @param $id int
     * @return \App\Model\DataResult
     */
    public function changeStatusToEnable( $id ){
        $query = "UPDATE Period
                  SET status = ".Utils::$STATUS_ENABLE.",
                  WHERE period_id = $id";
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     *
     * @return \App\Model\DataResult
     */
    public function deletePeriod($id)
    {
        $query = "DELETE FROM period
                  WHERE period_id = $id";
        return  self::executeQuery($query);
    }
}

