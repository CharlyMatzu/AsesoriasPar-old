<?php


//Registro de funcion que carga clase
spl_autoload_register("autoloader");
//Funcion que carga clase
function autoloader($class_name){
    $class_name = "$class_name.php";
//    Si no existe, se lanza excepcion
    if( !file_exists($class_name) )
        throw new \Exception("Error al cargar la clase: ". $class_name);

    include_once( $class_name );
}
