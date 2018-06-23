<?php namespace App\Persistence;

use App\Model\CareerModel;

use App\Model\DataResult;
use App\Utils;

class CareersPersistence extends Persistence {
    public function __construct(){}


    private $campos = "SELECT
                            c.career_id as 'id',
                            c.name as 'name',
                            c.short_name as 'short_name',
                            c.date_register as 'date_register',
                            c.status as 'status'
                            FROM career c ";

    /**
     * Se obtiene la carrera por ID
     *
     * @param $id
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getCareer_ById( $id ){
        $query = $this->campos."
                        WHERE career_id =".$id;
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Se obtiene todas las carreras registradas
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getCareers(){
        $query = $this->campos;
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /** ---------------------------- Nuevo --------------------------------- */

    /**
     * Se obtiene la carrera por nombre
     *
     * @param $name
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getCareer_ByName( $name ){
        $query = $this->campos."
                     WHERE name LIKE '%".$name."%'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Se obtiene la carrera por nombre corto (Abreviacion) de la carrera
     *
     * @param $short_name string
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getCareer_ByShortName($short_name ){
        $query = $this->campos."
                     WHERE short_name LIKE '%".$short_name."%'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Se obtiene la carrera por nombre corto (Abreviacion) de la carrera
     *
     * @param $name
     * @param null $career_id
     *
     * @return DataResult|bool
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getCareer_ByName_ShortName( $name, $career_id = null ){
        $query = $this->campos."
                     WHERE name = '$name' OR short_name = '$name'";

        //El id es para omitir el registro en la busqueda
        if( $career_id != null )
            $query = $this->campos."
                     WHERE ( name = '$name' OR short_name = '$name') AND career_id <> $career_id";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Método para insertar la carrera
     *
     * @param $name
     * @param $shortname
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function insertCareer( $name, $shortname ){
        $query = "INSERT INTO career (name, short_name)
                  VALUES('".$name."','".$shortname."')";
        return  self::executeQuery($query);
    }


    /**
     * Método para actualizar la carrera
     *
     * @param $career CareerModel
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function updateCareer( $career ){
        $query = "UPDATE career 
                      SET name = '".$career->getName()."', short_name = '".$career->getShortName()."' 
                      WHERE career_id = ".$career->getId();
        return  self::executeQuery($query);
    }

    /**
     * Método para eliminar la carrera
     *
     * @param $careerID
     *
     * @param $status
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function changeStatus($careerID, $status ){
        $query = "UPDATE career 
                      SET status = '$status'
                      WHERE career_id = $careerID";
        return  self::executeQuery($query);
    }


    /**
     * @param $id
     *
     * @return DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function deleteCareer($id)
    {
        $query = "DELETE FROM career 
                  WHERE career_id = $id";
        return  self::executeQuery($query);
    }

}
