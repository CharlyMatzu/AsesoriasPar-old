<?php namespace Service;

use Exceptions\ConflictException;
use Exceptions\InternalErrorException;
use Exceptions\NoContentException;
use Exceptions\NotFoundException;
use Persistence\Careers;
use Persistence\Plans;
use Persistence\SubjectsPersistence;
use Model\Subject;
use Utils;

class SubjectService{

    private $perSubjects;

    public function __construct(){
        $this->perSubjects = new SubjectsPersistence();
    }

    /**
     * @return array|null|string
     * @throws NoContentException
     * @throws InternalErrorException
     */
    public function getSubjects(){
        $result = $this->perSubjects->getSubjects();

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al obtener materias");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No se encontraron materias reistrados");
        else
            return $result->getData();
    }

    /**
     * @param $subject_id
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NotFoundException
     *///TODO: Regresar materias relacionadas
    public function getSubject_ById( $subject_id ){
        $result = $this->perSubjects->getSubject_ById( $subject_id );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al obtener la materia por ID");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe materia");
        else
            return $result->getData();
    }

    /**
     * @param $name
     * @return array|bool|string
     * @throws NoContentException
     * @throws InternalErrorException
     */
    public function getSubjects_ByName($name )
    {
        $result = $this->perSubjects->getSubjects_BySearch_Name( $name );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al obtener materia por nombre");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("");
        else
            return $result->getData();
    }

    /**
     * @param $careerID
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getCareerSubjects( $careerID ){
        $result = $this->perSubjects->getSubjects_ByCareer( $careerID );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al obtener materias");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No se encontraron materias reistrados");
        else
            return $result->getData();
    }


    /**
     * @param $plan
     * @return \mysqli_result
     * @throws NoContentException
     * @throws InternalErrorException
     */
    public function getPlanSubjects($plan )
    {
        $result = $this->perSubjects->getSubjects_ByPlan( $plan  );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al obtener materias");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No se encontraron materias reistrados");
        else
            return $result->getData();
    }

    /**
     * @param $semester
     * @return \mysqli_result
     * @throws InternalErrorException
     * @throws NoContentException
     */
    public function getSubject_BySemester( $semester )
    {
        $result = $this->perSubjects->getSubjects_BySemester( $semester );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al obtener materias");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NoContentException("No se encontraron materias reistrados");
        else
            return $result->getData();
    }


//    /**
//     * @param $id
//     * @return array
//     * @throws InternalErrorException
//     * @throws NoContentException
//     */
//    public function getScheduleSubjects_ByScheduleId($id ) {
//        $result = $this->perSubjects->getSubjects_ByScheduleId( $id );
//
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException("Ocurrio un error al obtener materias");
//        else if( Utils::isEmpty( $result->getOperation() ) )
//            throw new NoContentException("No se encontraron materias reistrados");
//        else
//            return Utils::makeArrayResponse(
//                "Materias registradas",
//                $result['data']
//            );
//    }



    //-----------------
    // ASESORIAS
    //-----------------

//    public function getCurrAvailScheduleSubs_SkipSutdent( $idStudent ){
//        $conSchedule = new ScheduleControl();
//        $cycle = $conSchedule->getCurrentPeriod();
//        if( !is_array($cycle) )
//            return $cycle;
//        else{
//            $result = $this->perSubjects->getAvailScheduleSubs_SkipStudent( $idStudent, $cycle['id'] );
//            if( $result === false )
//                return 'error';
//            else if( $result === null )
//                return null;
//            else{
//                return $result;
//            }
//        }
//    }



    /*--------------------------/Nuevo------------------------------------------- /*/







    /**------------------------INSERT , UPDATE , DELETE , SEARCH --------------------------------- **/

    /**
     * @param $subject Subject objeto de materia
     * @return array
     * @throws InternalErrorException
     * @throws ConflictException
     * @throws NotFoundException
     */
    public function registerSubject($subject ){

        //------------Verificamos que la materia no exista
        //TODO: verificar que no sea el mismo nombre dentro de la misma carrera/plan, se puede repetir en otros...
        $result = $this->isSubjectExist_ByName_ShortName($subject->getName(), $subject->getShortName());

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener materia por el nombre");

        else if( $result->getOperation() == true )
            throw new ConflictException("Nombre o Abreviacion ya existe");

        //------------Verificamos que la carrera exista
        $perCareer =  new Careers();
        $result = $perCareer->getCareer_ById( $subject->getCareer() );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener la carrera");

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("Carrera no existe");

        //------------Verificamos que el plan exista
        $perPlan =  new Plans();
        $result = $perPlan->getPlan_ById( $subject->getPlan() );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener el plan");

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("Plan no existe");


        //-------------Registrando materia
        $result = $this->perSubjects->insertSubject( $subject );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al registrar la materia", $result->getErrorMessage());
        else
            return Utils::makeArrayResponse("Se registro materia con éxito");
    }

    //--------------------UPDATE SUBJECT--------------------

    /**
     * @param $subject Subject
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ConflictException
     */
    public function updateSubject( $subject ){


        //Se obtiene materia y verifica existencia
        $result = $this->perSubjects->getSubject_ById( $subject->getId() );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo comprobar existencia de materia por ID");
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe materia");

        //-----------Verificando dato que cambio
        $sub_Name = $result->getData()[0]['name'];
        $sub_ShortName = $result->getData()[0]['short_name'];

        //--Si cambio el nombre
        if( $subject->getName() !== $sub_Name ){
            $result = $this->isSubjectExist_ByName( $subject->getName() );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("Error al buscar materia por nombre");
            else if( $result->getOperation() == true )
                throw new ConflictException("Nombre ya existe");
        }


        //Si cambio la abreviacion
        if( $subject->getShortName() !== $sub_ShortName ) {
            $result = $this->isSubjectExist_ByShortName($subject->getShortName());

            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("Error al buscar materia por abreviacion");
            else if( $result->getOperation() == true )
                throw new ConflictException("Abreviacion ya existe");
        }


        //------------Verificamos que la carrera exista
        $perCareer =  new Careers();
        $result = $perCareer->getCareer_ById( $subject->getCareer() );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener la carrera");

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("Carrera no existe");

        //------------Verificamos que el plan exista
        $perPlan =  new Plans();
        $result = $perPlan->getPlan_ById( $subject->getPlan() );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener el plan");

        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("Plan no existe");


        // Si sale bien, Inicia registro de Career
        $result = $this->perSubjects->updateSubject( $subject);

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo actualizar materia");
        else
            return Utils::makeArrayResponse(
                "Materia actualizado con exito"
            );
    }

    //---------------------DELETE SUBJECT--------------------

    /**
     * @param $subjectID
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function disableSubject($subjectID ){

        $result = $this->isSubjectExist_ById( $subjectID );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al obtener materia por ID");
        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe materia");

        $result = $this->perSubjects->changeStatusToDeleted( $subjectID );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al deshabilitar materia");
        return Utils::makeArrayResponse(
            "Se deshabilito materia con exito"
        );
    }


    /**
     * @param $name
     * @return \Model\DataResult
     */
    public function isSubjectExist_ByName( $name )
    {
        $result = $this->perSubjects->getSubject_ByName( $name );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }

    /**
     * @param $shortName
     * @return \Model\DataResult
     */
    public function isSubjectExist_ByShortName($shortName )
    {
        $result = $this->perSubjects->getSubject_ByShortName( $shortName );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }

    /**
     * @param $name
     * @param $short
     * @return \Model\DataResult
     */
    public function isSubjectExist_ByName_ShortName( $name, $short )
    {
        $result = $this->perSubjects->getSubject_ByName_ShortName( $name, $short );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }


    /**
     * @param $id
     * @return \Model\DataResult
     */
    public function isSubjectExist_ById( $id )
    {
        $result = $this->perSubjects->getSubject_ById( $id );

        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        return $result;
    }

    //----------------------
    // MATERIAS RELACIONADAS
    //----------------------

    /**
     * @param $mainSubID int ID de la materia principal
     * @param $subjectsArray array array de materias relacionadas
     * @return array
     * @throws NotFoundException
     * @throws InternalErrorException
     */
    public function addSimilarySubjetcs($mainSubID, $subjectsArray){

        //Verificando que materia principal exista
        $result = $this->isSubjectExist_ById( $mainSubID );
        //Comprobando errores
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo obtener materia", $result->getErrorMessage());
        else if( Utils::isEmpty( $result->getOperation() ) )
            throw new NotFoundException("No existe materia principal");

        //TODO: verificar que no esten ya relacionadas
        //TODO: Verificar que materia no sea la misma que principal
        //TODO: Verificar que materia no se haya relacionado anteriomente (durante registros)


        //TODO: Usar transacciones
        foreach ( $subjectsArray as $subID ){
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("No se pudo obtener materia", $result->getErrorMessage());
            else if( Utils::isEmpty( $result->getOperation() ) )
                throw new NotFoundException("No existe materia principal");

            //Se registra
            $result = $this->perSubjects->setSubjectRelation( $mainSubID, $subID );

            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("No se pudo relacionar materia", $result->getErrorMessage());
        }

        return Utils::makeArrayResponse("Materias relacionadas con éxito");

    }

}

