<?php namespace App\Service;

use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;

use App\Model\DataResult;
use App\Persistence\CareersPersistence;
use App\Model\CareerModel;
use App\Utils;

class CareerService{

    private $perCareers;

    public function __construct(){
        $this->perCareers = new CareersPersistence();
    }


    /**
     * Metodo que retorna un Array de todas las carreras disponibles
     * @return array|null|string
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCareers(){

        $result = $this->perCareers->getCareers();

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCareers", "Ocurrio un error al obtener careras", $result->getErrorMessage());

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No hay carreras registradas");

        else
            return $result->getData();
    }



    /**
     * Se obtiene la carrera por ID
     * @param $id
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getCareer_ById( $id ){

        $result = $this->perCareers->getCareer_ById( $id );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCareerById", "Ocurrio un error al obtener la carera", $result->getErrorMessage());

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe carrera");

        else
            return $result->getData();
    }

    /**
     * Se obtiene la carrera mediante el nombre s
     * @param $name
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getCareer_ByName( $name ){

        $result = $this->perCareers->getCareer_ByName ($name );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCareerByName","Ocurrio un error al obtener la carera", $result->getErrorMessage());

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe carrera");
        else
            return $result->getData();
    }

    /**
     * Se obtiene la carrera mediante en nombre corto (Abreviacion) de la carrera
     * @param $short_name
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getCareer_ByShort_Name( $short_name ){
        //NULL O ARRAY
        $result = $this->perCareers->getCareer_ByShortName( $short_name );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCarrerByName", "Ocurrio un error al obtener la carera", $result->getErrorMessage());

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe carrera");

        else
            return $result->getData();
    }


    /** -----------------------INSERT , UPDATE , DELETE , SEARCH -------------------------- */

    /**
     * @param $name
     * @param $short_name
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertCareers( $name, $short_name ){

        //Verificamos que la carrera no exista
        try{
            $this->getCareer_ByName_ShortName( $name );
            throw new ConflictException("Nombre ya existe");
            //Si no encuentra nada, no hay problema
        }catch (NoContentException $e){}
        try{
            $this->getCareer_ByName_ShortName( $short_name );
            throw new ConflictException("abreviacion ya existe");
            //Si no encuentra nada, no hay problema
        }catch (NoContentException $e){}



        // Si sale bien, Inicia registro de CareerModel
        $result = $this->perCareers->insertCareer( $name, $short_name );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("insertCareer", "Ocurrio un error al registrar la carrera", $result->getErrorMessage());

    }

    /**
     * Metodo para actualizar la carrera
     *
     * @param $career CareerModel
     *
     * @throws RequestException
     */
    public function updateCarrers( $career ){

        //Verificamos si la carrera existe
        $career_aux = $this->getCareer_ById( $career->getId() );

        //-----------verificamos que dato cambio
        //---Nombre
        try{
            //Si cambio nombre, se verifica
            if( $career_aux['name'] != $career->getName() ) {
                //Debe lanzar exception para que sea correcto
                $this->getCareer_ByName_ShortName( $career->getName(), $career->getId() );
                throw new ConflictException("Nombre ya existe");
            }
            //Si no encuentra nada, no hay problema
        }catch (NoContentException $e){}

        //-------shortname
        try{
            //Si cambio nombre, se verifica
            if( $career_aux['short_name'] != $career->getShortName() ) {
                //Debe lanzar exception para que sea correcto
                $this->getCareer_ByName_ShortName( $career->getShortName(), $career->getId() );
                throw new ConflictException("Abreviacion ya existe");
            }
            //Si no encuentra nada, no hay problema
        }catch (NoContentException $e){}


        // Si sale bien, Inicia REGISTRO de CareerModel
        $result = $this->perCareers->updateCareer( $career );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("updateCareers",
                "No se pudo actualizar carrera", $result->getErrorMessage());
    }

    /**
     * @param $career_id
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function deleteCareer($career_id)
    {
        //Se verifica si carrera existe
        $this->getCareer_ById( $career_id );

        $result = $this->perCareers->deleteCareer( $career_id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("deleteCareer", "No se pudo eliminar carrera", $result->getErrorMessage());
    }

    /**
     * Meotodo para eliminar una carrera mediante el ID
     * @param $careerID
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function disableCareer($careerID ){
        //Verificamos si la carrera existe
        $this->getCareer_ById( $careerID );

        $result = $this->perCareers->changeStatusToDeleted( $careerID );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("disableCareer", "No se pudo deshabilitar carrera", $result->getErrorMessage());
    }


    /**
     * Meotodo para eliminar una carrera mediante el ID
     *
     * @param $careerID
     *
     * @return void
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function enableCareer($careerID ){
        //Verificamos si la carrera existe
        //REGRESA TRUE O FALSE
        $this->getCareer_ById($careerID);
        $result = $this->perCareers->changeStatusToEnable( $careerID );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("enableCareers", "No se pudo habilitar carrera", $result->getErrorMessage());
    }


//    /**
//     * Metodo para verificar que la carrera existe o no mediante el ID de la carrera
//     * @param $id
//     * @return bool|DataResult
//     */
//    public function isCareerExist_ById($id ){
//
//        $result = $this->perCareers->getCareer_ById( $id );
//
//        if( Utils::isSuccessWithResult( $result->getOperation() ) )
//            $result->setOperation(true);
//
//        else if( Utils::isEmpty( $result->getOperation() ) )
//            $result->setOperation(false);
//
//        return $result;
//    }

    /**
     * Metodo para verificar si existe la carrera mediante el nombre de la carrera
     *
     * @param $name string
     *
     * @param null $career_id
     *
     * @return \mysqli_result|null
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCareer_ByName_ShortName($name, $career_id = null){

        $result = $this->perCareers->getCareer_ByName_ShortName( $name, $career_id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCareer_ByName_ShortName",
                "Error al obtener carrera por nombre/abreviacion", $result->getErrorMessage() );
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");

        return $result->getData();
    }




    public static function makeObject_career( $c ){
        $career = new CareerModel();
        $career->setId( $c['id'] );
        $career->setName( $c['name'] );
        $career->setShortName( $c['short_name'] );
        $career->setRegisterDate( $c['date_register'] );
        $career->setStatus( $c['status'] );
        return $career;
    }


}