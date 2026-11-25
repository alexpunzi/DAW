<?php

function conectar_DB(): mysqli{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
    //le el fichero de configuracion 
    $config = parse_ini_file(__DIR__ . '/config.ini', true); 

    if($config === false || !isset($config['DB'])){
        throw new RuntimeException('No se pudo leer el ficher de configuracion'); 
    }

    $db = $config['DB']; 

    //crea la conexión orientada a objetos con ext/mysqli
    $mysqli = new mysqli(
        $db['Server'],
        $db['User'],
        $db['Password'],
        $db['Database']
    );

    $mysqli->set_charset('utf8mb4');

    return $mysqli;

}