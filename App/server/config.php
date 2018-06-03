<?php

    //Configuracion para UTF-8
    // header('Content-Type: text/html; charset=UTF-8');

    //------------------
    //  DEBUG
    //  0 = Desactivar
    //  1 = Warning / Errors
    //  2 = Errors / Warning / Notices
    //  3 = Errors / Warning / Notices and autoload debug
    //------------------
    define("DEBUG", 0);

    if( DEBUG == 1 )
        error_reporting(0);
    else if( DEBUG == 1 )
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
    else if( DEBUG >= 2 )
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);



    //------------------
    //  VARIABLES Y CONSTANTES
    //------------------

    //----------------  Directorios
    define("DS", DIRECTORY_SEPARATOR);
    define('ROOT_PATH', __DIR__ );
    define("VENDOR_PATH",  ROOT_PATH . DS . "vendor");
    define("TEMPLATE_PATH", ROOT_PATH . DS . "template");
    define('EST_PATH',  ROOT_PATH . DS . "estudiantes");

    
    //------------------
    //  INCLUDES Y REQUIRES
    //------------------
    //include_once ROOT_PATH . DS . "autoload.php";
    //include_once VENDOR_PATH. DS ."autoload.php";

    define("SERVER_URL", "http://api.asesoriaspar.com/");
