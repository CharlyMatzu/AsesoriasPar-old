<?php

    require_once '../config.php';

    use Control\ControlUsuarios;
    use Control\Funciones;
    use Objetos\Usuario;
    use Objetos\Estudiante;

    if( !isset($_POST['user_register']) ){
        Funciones::makeJsonResponse(
            "signup",
            'error',
            "No se envío la información esperada"
        );
        exit;
    }

    $conUsers = new ControlUsuarios();

    //JSON to JSON PHP Object
    $json = $_POST['user_register'];
    $registerJSON = json_decode($json);


    //Obteniendo los arrays correspondientes
    $user = $registerJSON->user;
    $student = $registerJSON->student;


    //Crear usuario
    $userObj = new Usuario();
    $userObj->setUsername($user->username);
    $userObj->setEmail($user->email);
    $userObj->setPassword($user->password);


    //Crear Estudiante
    $studentObj = new Estudiante();
    $studentObj->setFirstName( $student->first_name );
    $studentObj->setLastname( $student->last_name );
    $studentObj->setIdItson( $student->id_itson );
    $studentObj->setCareer( $student->career );


    //Inserción un nuevo

    $result = $conUsers->insertUserAndStudent($userObj, $studentObj);

    //TODO: Enviar mensajes cuando algo ya existe aparte del error

    //--------Ocurrio un error
    if( $result['result'] === 'error' ){
        echo Funciones::makeJsonResponse(
            "signup",
            'error',
            "Ocurrio un error al registrar usuario",
            $result['message']
        );

    }


    //--------Algun valor ya existe
    if( $result['result'] == false ){

        $type = $result['type'];
        if( $type === "username" ){
            echo Funciones::makeJsonResponse(
                "signup",
                false,
                "Nombre de usuario ya existe"
            );
        }
        else if( $type === "email" ){
            echo Funciones::makeJsonResponse(
                "signup",
                false,
                "Correo ya existe"
            );
        }



    }
    //--------OK
    else{
        //TODO: enviar correo a admin
        //TODO: enviar correo de verificacion al usuario para que pueda inciiar sesion
        echo Funciones::makeJsonResponse(
            "signup",
            true,
            "Registrado con éxito"
        );

    }


?>