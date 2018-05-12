<?php namespace Control;

use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Objects\DataResult;
use Persistence\Careers;
use Objects\Career;
use Utils;

class CareerControl{

    private $perCareers;

    public function __construct(){
        $this->perCareers = new Careers();
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
            throw new InternalErrorException("Ocurrio un error al obtener careras", $result->getErrorMessage());

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
            throw new InternalErrorException("Ocurrio un error al obtener la carera");

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
            throw new InternalErrorException("Ocurrio un error al obtener la carera");

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
            throw new InternalErrorException("Ocurrio un error al obtener la carera");

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe carrera");

        else
            return $result->getData();
    }


    /** -----------------------INSERT , UPDATE , DELETE , SEARCH -------------------------- */

    /**
     * @param $name
     * @param $short_name
     * @return mixed
     * @throws ConflictException
     * @throws InternalErrorException
     */
    public function insertCareers( $name, $short_name ){
        //Verificamos que la carrera no exista
        //REGRESA TRUE O FALSE

        $result = $this->isCareerExist_ByName_ShortName($name, $short_name);

        //Si ocurrio un error
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener carrera", $result->getErrorMessage());
        //Si existe
        else if( $result->getOperation() == true )
           throw new ConflictException("Nombre o abreviacion ya existe");


        // Si sale bien, Inicia registro de Career
        $result = $this->perCareers->insertCareer( $name, $short_name );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al registrar la carrera", $result->getErrorMessage());
        else
            return Utils::makeArrayResponse(
                "Se registro carrera con éxito",
                "$name - $short_name"
            );
    }

    /**
     * Metodo para actualizar la carrera
     * @param $career Career
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function updateCarrers( $career ){

        $id = $career->getId();
        $name = $career->getName();
        $short_name = $career->getShortName();

        //Verificamos si la carrera existe
        //REGRESA TRUE O FALSE

        $result = $this->isCareerExist_ById($id);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo comprobar existencia de Carrera por ID");

        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe ID de carrera");


        //----------Verificando si el nombre ya existe
        $result = $this->isCareerExist_ByName_ShortName($name, $short_name);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo comprobar existencia de carrera por Nombre");
        else if( $result->getOperation() == true )
            throw new ConflictException("Nombre de carrera ya existe");


        // Si sale bien, Inicia REGISTRO de Career
        $result = $this->perCareers->updateCareer( $career );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo actualizar carrera");
        else
            return Utils::makeArrayResponse("Carrera actualizada con exito" );

    }

    /**
     * Meotodo para eliminar una carrera mediante el ID
     * @param $careerID
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function disableCareer($careerID ){
        //Verificamos si la carrera existe
        //REGRESA TRUE O FALSE
        $result = $this->isCareerExist_ById($careerID);


        if( Utils::isError( $result->getOperation() ) )
            throw new NotFoundException("Error al obtener Carrera por ID");

        else if( $result->getOperation() == false )
            throw new NotFoundException("Carrera no existe");


        $result = $this->perCareers->changeStatusToDeleted( $careerID );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo deshabilitar carrera");
        else
            return Utils::makeArrayResponse(
                "Se deshabilitó carrera con exito",
                $careerID
            );
    }


    /**
     * Meotodo para eliminar una carrera mediante el ID
     * @param $careerID
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function enableCareer($careerID ){
        //Verificamos si la carrera existe
        //REGRESA TRUE O FALSE
        $result = $this->isCareerExist_ById($careerID);


        if( Utils::isError( $result->getOperation() ) )
            throw new NotFoundException("Error al obtener Carrera por ID");

        else if( $result->getOperation() == false )
            throw new NotFoundException("Carrera no existe");


        $result = $this->perCareers->changeStatusToEnable( $careerID );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo habilitar carrera");
        else
            return Utils::makeArrayResponse(
                "Se habilitó carrera con exito",
                $careerID
            );
    }


    /**
     * Metodo para verificar que la carrera existe o no mediante el ID de la carrera
     * @param $id
     * @return bool|DataResult
     */
    public function isCareerExist_ById($id ){

        $result = $this->perCareers->getCareer_ById( $id );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);

        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }

    /**
     * Metodo para verificar si existe la carrera mediante el nombre de la carrera
     * @param $name
     * @param $short
     * @return bool|DataResult
     */
    public function isCareerExist_ByName_ShortName( $name, $short ){

        $result = $this->perCareers->getCareer_ByName_ShortName( $name, $short );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);

        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }

    /**
     * Metodo para verificar si existe la carrera mediante el nombre corto (Abreviacion) de la carrera
     * @param $short_name
     * @return DataResult
     */
    public function isCareerExist_ByShort_name( $short_name ){

        $result = $this->perCareers->getCareer_ByShortName( $short_name );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);

        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }


//    public static function makeObject_career( $c ){
//        $career = new Career();
//        $career->setId( $c['id'] );
//        $career->setName( $c['name'] );
//        $career->setShortName( $c['short_name'] );
//        $career->setRegisterDate( $c['date_register'] );
//        return $career;
//    }
}