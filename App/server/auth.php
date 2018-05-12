<?php

    require_once '../config.php';

    use Control\ControlUsuarios;
    use Control\ControlEstudiantes;
    use Control\Sesiones;
    use Control\Funciones;

    //TODO: cambiar por JSON
    if( !isset($_POST['user']) || !isset($_POST['pass']) ){
        Funciones::makeJsonResponse(
            "auth",
            'error',
            "No se envío la información esperada"
        );
        exit;
    }

    //Se obtienen los datos de peticion
	$username = $_POST['user'];
	$password = $_POST['pass'];

	$conUsers = new ControlUsuarios();
	$conStudents = new ControlEstudiantes();

	//------------------------------- AUTENTICACION USUARIO
	//Busca usuario en base de datos
    $user = $conUsers->getUser_ByAuth($username, $password);

    //--------------- Error
    if( $user == 'error' ){
        echo Funciones::makeJsonResponse(
            "auth",
            "error",
            "Ocurrio un error al autenticar usuario"
        );
        exit;
    }

    //---------------Si no se encontro usuario
    else if( $user == null ){
        echo Funciones::makeJsonResponse(
            "auth",
            'null',
            "No se encontró al usuario"
        );
        exit;
    }

    //---------------Usuario encontado
    else{
        //Establece la session con los datos del usuario
        Sesiones::setUserSession( $user );

        //------------------------------- AUTENTICACION ESTUDIANTE
        if( $user->getRole() == 'estudiante' ) {
            $student = $conStudents->getStuden_ByUserId( $user->getId() );

            //Error
            if( $student == "error" ){
                //Borra sesion
                Sesiones::destroySession();
                //Mensaje
                echo Funciones::makeJsonResponse(
                    "auth",
                    "error",
                    "Ocurrio un error al buscar estudiante"
                );
                exit;
            }
            //No existe
            else if( $student == null ){
                //Borra sesion
                Sesiones::destroySession();
                //Mensaje
                echo Funciones::makeJsonResponse(
                    "auth",
                    'null',
                    "No se encontró al estudiante"
                );
                exit;
            }
            else{
                Sesiones::setStudentSession( $student );
            }
        }

        //Se envia la respuesta
        echo Funciones::makeJsonResponse(
            "auth",
            true,
            "Usuario identificado",
            $user->getRole()
        );
    }



	

?>