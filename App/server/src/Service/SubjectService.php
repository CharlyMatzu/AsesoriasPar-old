<?php namespace App\Service;

use App\Exceptions\ConflictException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\NoContentException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RequestException;
use App\Persistence\CareersPersistence;
use App\Persistence\PlansPeristence;
use App\Persistence\SubjectsPersistence;
use App\Model\Subject;
use App\Utils;

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
            throw new NotFoundException("No existe materia", $subject_id);
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
     *
     * @return void
     * @throws ConflictException
     * @throws InternalErrorException
     * @throws RequestException
     */
    public function insertSubject( $subject ){

        //------------Verificamos que la materia no exista
        //TODO: verificar que no sea el mismo nombre dentro de la misma carrera/plan, se puede repetir en otros...
        $result = $this->isSubjectExist_ByName_ShortName(
            $subject->getName(), $subject->getShortName(),
            $subject->getPlan(), $subject->getCareer() );

        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("No se pudo verificar materia");
        else if( $result->getOperation() == true )
            throw new ConflictException("Nombre o Abreviacion ya existe");


        //------------Verificamos que la carrera exista
        try{
            $careerService =  new CareerService();
            $careerService->getCareer_ById( $subject->getCareer() );
        }catch (RequestException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //------------Verificamos que el plan exista
        try{
            $planService =  new PlanService();
            $planService->getPlan_ById( $subject->getPlan() );
        }catch (RequestException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //-------------Registrando materia
        $result = $this->perSubjects->insertSubject( $subject );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al registrar la materia");
    }

    //--------------------UPDATE SUBJECT--------------------

    /**
     * @param $subject Subject
     * @throws RequestException
     */
    public function updateSubject( $subject ){
        //------------Verificamos que la materia no exista

        //TODO: verificar por cambio de nombre
//        $result = $this->isSubjectExist_ByName_ShortName(
//            $subject->getName(), $subject->getShortName(),
//            $subject->getPlan(), $subject->getCareer() );

//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException("No se pudo verificar materia");
//        else if( $result->getOperation() == true )
//            throw new ConflictException("Nombre o Abreviacion ya existe");


        //------------Verificamos que la carrera exista
        try{
            $careerService =  new CareerService();
            $careerService->getCareer_ById( $subject->getCareer() );
        }catch (RequestException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //------------Verificamos que el plan exista
        try{
            $planService =  new PlanService();
            $planService->getPlan_ById( $subject->getPlan() );
        }catch (RequestException $e){
            throw new RequestException($e->getMessage(), $e->getStatusCode());
        }

        //-------------Registrando materia
        $result = $this->perSubjects->updateSubject( $subject );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Ocurrio un error al actualizar la materia");
    }


    //---------------------DELETE SUBJECT--------------------

    /**
     * @param $subjectID
     * @param $new_status
     *
     * @return void
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function changeStatus($subjectID, $new_status ){

        $result = $this->isSubjectExist_ById( $subjectID );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al obtener materia por ID");
        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe materia");

        if( $new_status == Utils::$STATUS_DISABLE ){
            $result = $this->perSubjects->changeStatusToDeleted( $subjectID );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("Error al deshabilitar materia");
        }
        else if( $new_status == Utils::$STATUS_ENABLE ){
            $result = $this->perSubjects->changeStatusToEnable( $subjectID );
            if( Utils::isError( $result->getOperation() ) )
                throw new InternalErrorException("Error al habilitar materia");
        }
    }

    /**
     * @param $subjectID
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function deleteSubject($subjectID ){

        $result = $this->isSubjectExist_ById( $subjectID );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al obtener materia por ID");
        else if( $result->getOperation() == false )
            throw new NotFoundException("No existe materia");

        $result = $this->perSubjects->deleteSubject( $subjectID );
        if( Utils::isError( $result->getOperation() ) )
            throw new InternalErrorException("Error al eliminar materia");
    }



    /**
     * @param $name String Career name/short_name
     * @param $short_name
     * @param $plan int Plan Id
     * @param $career int Career id
     *
     * @return \Model\DataResult
     */
    public function isSubjectExist_ByName_ShortName( $name, $short_name, $plan, $career )
    {
        //----Busca por name
        $result = $this->perSubjects->getSubject_ByName_ShortName( $name, $plan, $career );
        if( Utils::isSuccessWithResult( $result->getOperation() ) )
            $result->setOperation(true);
        else if( Utils::isEmpty( $result->getOperation() ) )
            $result->setOperation(false);

        //----Busca por short_name
        $result = $this->perSubjects->getSubject_ByName_ShortName( $short_name, $plan, $career );
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

//    /**
//     * @param $mainSubID int ID de la materia principal
//     * @param $subjectsArray array array de materias relacionadas
//     * @return array
//     * @throws NotFoundException
//     * @throws InternalErrorException
//     */
//    public function addSimilarySubjetcs($mainSubID, $subjectsArray){
//
//        //Verificando que materia principal exista
//        $result = $this->isSubjectExist_ById( $mainSubID );
//        //Comprobando errores
//        if( Utils::isError( $result->getOperation() ) )
//            throw new InternalErrorException("No se pudo obtener materia", $result->getErrorMessage());
//        else if( Utils::isEmpty( $result->getOperation() ) )
//            throw new NotFoundException("No existe materia principal");
//
//        //TODO: verificar que no esten ya relacionadas
//        //TODO: Verificar que materia no sea la misma que principal
//        //TODO: Verificar que materia no se haya relacionado anteriomente (durante registros)
//
//
//        //TODO: Usar transacciones
//        foreach ( $subjectsArray as $subID ){
//            if( Utils::isError( $result->getOperation() ) )
//                throw new InternalErrorException("No se pudo obtener materia", $result->getErrorMessage());
//            else if( Utils::isEmpty( $result->getOperation() ) )
//                throw new NotFoundException("No existe materia principal");
//
//            //Se registra
//            $result = $this->perSubjects->setSubjectRelation( $mainSubID, $subID );
//
//            if( Utils::isError( $result->getOperation() ) )
//                throw new InternalErrorException("No se pudo relacionar materia", $result->getErrorMessage());
//        }
//
//        return Utils::makeArrayResponse("Materias relacionadas con éxito");
//
//    }


}

