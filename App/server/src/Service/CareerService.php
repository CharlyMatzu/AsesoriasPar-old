<?php namespace App\Service;



use App\Auth;
use App\Exceptions\Request\ConflictException;
use App\Exceptions\Request\InternalErrorException;
use App\Exceptions\Request\NoContentException;
use App\Exceptions\Request\NotFoundException;
use App\Persistence\CareersPersistence;
use App\Model\CareerModel;
use App\Utils;

class CareerService{

    private $perCareers;

    public function __construct(){
        $this->perCareers = new CareersPersistence();
    }


    /**
     * Método que retorna un Array de todas las carreras disponibles
     * @return array|null|string
     * @throws InternalErrorException
     * @throws NoContentException
     * @throws \App\Exceptions\Request\UnauthorizedException
     */
    public function getCareers(){

        //Validación se sesión
        if( Auth::$isSessionON ){
            if( Auth::isStaffUser() )
                $result = $this->perCareers->getCareers();
            else
                $result = $this->perCareers->getEnabledCareers();
        }
        else
            $result = $this->perCareers->getEnabledCareers();



        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCareers", "Ocurrió un error al obtener careras", $result->getErrorMessage());

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No hay carreras registradas");

        else
            return $result->getData();
    }


    /**
     * Se obtiene la carrera por ID
     *
     * @param $id
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function getCareer_ById( $id ){

        $result = $this->perCareers->getCareer_ById( $id );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("getCareerById", "Ocurrió un error al obtener la carera", $result->getErrorMessage());

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
            throw new InternalErrorException("getCareerByName","Ocurrió un error al obtener la carera", $result->getErrorMessage());

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
            throw new InternalErrorException("getCarrerByName", "Ocurrió un error al obtener la carera", $result->getErrorMessage());

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe carrera");

        else
            return $result->getData();
    }


    /**
     * @param $id
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function getSubjects_ByCareer($id)
    {
        $this->getCareer_ById($id);
        $subServ = new SubjectService();
        return $subServ->getSubjects_ByCareer( $id );
    }

    /**
     * @param $id
     *
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws NoContentException
     */
    public function getStudents_ByCareer($id)
    {
        $this->getCareer_ById($id);
        $stuServ = new StudentService();
        return $stuServ->getStudents_ByCareer( $id );
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
            throw new InternalErrorException("insertCareer", "Ocurrió un error al registrar la carrera", $result->getErrorMessage());

    }

    /**
     * Método para actualizar la carrera
     *
     * @param $career CareerModel
     *
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws NotFoundException
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

        //eliminar a los usuarios donde sus estudiantes estén relacionados a dicha carrera para que no haya problemas
        //en registros posteriores
        $userServ = new UserService();
        $userServ->deleteUsers_ByStudentCareer( $career_id );

        //Elimina carreras
        $result = $this->perCareers->deleteCareer( $career_id );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("deleteCareer", "No se pudo eliminar carrera", $result->getErrorMessage());
    }

    /**
     * Método para eliminar una carrera mediante el ID
     * @param $careerID
     * @param $status
     *
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($careerID, $status ){
        //Verificamos si la carrera existe
        $this->getCareer_ById( $careerID );

        $result = $this->perCareers->changeStatus( $careerID, $status );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("disableCareer", "Erro al cambiar estado de carrera", $result->getErrorMessage());
    }



    /**
     * Método para verificar si existe la carrera mediante el nombre de la carrera
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