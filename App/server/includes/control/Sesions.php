<?php namespace Control;

use Objects\Career;
use Objects\User;
use Objects\Student;
use Control\StudentControl;
use Control\UserControl;
session_start();


class Sesions{

    public static $ALUMNO = 'alumno';
    public static $ASESOR = 'asesor';

    //---------------- LOGGIN

    public static function setUserSession(User $user){
        //Datos del usuario
        $_SESSION['user']['id']       = $user->getId();
        $_SESSION['user']['username'] = $user->getUsername();
        $_SESSION['user']['role']     = $user->getRole();
    }

    public static function setStudentSession(Student $student){
        //Datos del usuario
        $_SESSION['student']['id']      = $student->getIdStudent();
        $_SESSION['student']['name']    = $student->getFirstName() . " " . $student->getLastname();
        $_SESSION['student']['career']  = $student->getCareer();
    }


    public static function checkCurrentSession(){
        $conUsers = new UserControl();

        //Si existe sesion de usuario activa
        if( self::isSessionON() ){
            //Obtiene usuario para actualizar sesion
            $user = $conUsers->getUser_ById( self::getUserId() );

            //Vericia que usuario realmente exista
            //TODO: verificar por estado de usuario y si existe
            if( $user == null || $user == 'error' ) {
                //reset de sesion
                self::destroySession();
            }
            else{
                $conStudens = new StudentControl();
                $conStudens->getStudent_ById( self::getStudentId() );
                //TODO: si no es student, redireccionar
                if( $user == null || $user == 'error' ) {
                    //reset de sesion
                    self::destroySession();
                }{
                    //TODO: actualizar datos (user y student)
                }
            }
        }
    }


    public static function destroySession(){
        unset( $_SESSION['user'] );
        unset( $_SESSION['student'] );
        session_destroy();
    }


    //---------------- DATOS
    public static function isSessionON(){
        if( isset( $_SESSION['user']['id'] ) )
            return true;
        else
            return false;
    }


    //--------------------- ROL
    public static function isAdmin(){
        if( self::isSessionON() ){
            if( $_SESSION['user']['role'] == "administrator" )
                return true;
            else
                return false;
        }
        else
            return null;
    }


    public static function isStudent(){
        if( self::isSessionON() ){
            if( $_SESSION['user']['role'] == "student" )
                return true;
            else
                return false;
        }
        else
            return null;
    }


    //--------------------- USUARIO
    public static function getUserId(){
        return $_SESSION['user']['id'];
    }


    //--------------------- ESTUDIANTE
    public static function isTypeSelected(){
        if( self::isSessionON() ){
            if( isset( $_SESSION['student']['type'] ) )
                return true;
            else
                return false;
        }
        else
            return false;
    }

    public static function setStudentType($type){
        $_SESSION['student']['type'] = $type;
    }


    public static function isAsesor(){
        return ( $_SESSION['student']['type'] == self::$ASESOR )? true : false;
    }

    public static function isAlumno(){
        return ( $_SESSION['student']['type'] == self::$ALUMNO )? true : false;
    }

    public static function getStudentType(){
        return $_SESSION['student']['type'];
    }

    //--------------------- ESTUDIANTE DATOS

    public static function getStudentId(){
        return $_SESSION['student']['id'];
    }

    public static function getStudentName(){
        return $_SESSION['student']['name'];
    }

    /**
     * @return Career|int
     */
    public static function getStudentCarrer(){
        return $_SESSION['student']['career'];
    }




}

?>