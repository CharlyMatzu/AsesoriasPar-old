<?php namespace Persistence;

use Model\Career;
use Model\DataResult;
use Utils;

class Careers extends Persistence {
    public function __construct(){}


    //TODO: agregar estado
    private $campos = "SELECT
                            c.career_id as 'id',
                            c.name as 'name',
                            c.short_name as 'short_name',
                            c.date_register as 'date_register',
                            c.status as 'status'
                            FROM career c ";

    /**
     * Se obtiene la carrera por ID
     * @param $id
     * @return DataResult
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
     */
    public function getCareers(){
        $query = $this->campos;
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /** ---------------------------- Nuevo --------------------------------- */

    /**
     * Se obtiene la carrera por nombre
     * @param $name
     * @return DataResult
     */
    public function getCareer_ByName( $name ){
        $query = $this->campos."
                     WHERE name LIKE '%".$name."%'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Se obtiene la carrera por nombre corto (Abreviacion) de la carrera
     * @param $short_name string
     * @return DataResult
     */
    public function getCareer_ByShortName($short_name ){
        $query = $this->campos."
                     WHERE short_name LIKE '%".$short_name."%'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Se obtiene la carrera por nombre corto (Abreviacion) de la carrera
     * @param $name
     * @param $short_name string
     * @return DataResult|bool
     */
    public function getCareer_ByName_ShortName( $name, $short_name ){
        $query = $this->campos."
                     WHERE name = '$name' OR short_name = '$short_name'";
        //Obteniendo resultados
        return self::executeQuery($query);
    }

    /**
     * Metodo para insertar la carrera
     * @param $name
     * @param $shortname
     * @return DataResult
     */
    public function insertCareer( $name, $shortname ){
        $query = "INSERT INTO career (name, short_name)
                  VALUES('".$name."','".$shortname."')";
        return  self::executeQuery($query);
    }


    /**
     * Metodo para actualizar la carrera
     * @param $career Career
     * @return DataResult
     */
    public function updateCareer( $career ){
        $query = "UPDATE career 
                      SET name = '".$career->getName()."', short_name = '".$career->getShortName()."' 
                      WHERE career_id = ".$career->getId();
        return  self::executeQuery($query);
    }

    /**
     * Metodo para eliminar la carrera
     * @param $careerID
     * @return DataResult
     */
    public function changeStatusToDeleted($careerID ){
        $query = "UPDATE career 
                      SET status = ". Utils::$STATUS_DELETED ."
                      WHERE career_id = $careerID";
        return  self::executeQuery($query);
    }


    /**
     * Metodo para eliminar la carrera
     * @param $careerID
     * @return DataResult
     */
    public function changeStatusToEnable( $careerID ){
        $query = "UPDATE career 
                      SET status = ". Utils::$STATUS_ACTIVE ."
                      WHERE career_id = $careerID";
        return  self::executeQuery($query);
    }

    //TODO: añadir opcion para deliminar definitivamente

}
