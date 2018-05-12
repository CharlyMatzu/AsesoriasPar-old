<?php

    //Registro de funcion que carga clase
    spl_autoload_register("autoload");

    //Funcion que carga clase
    function autoload($class){
        $class = __DIR__ . "\\" . str_replace("\\", DS, $class) . ".php";

        //Para pruebas
        if( DEBUG == 3 )
            echo $class . "<br>";

        if( !file_exists($class) )
            throw new \Exception("Error al cargar la clase: ". $class);

        require_once($class);
    }

?>