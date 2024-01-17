<?php
    // VALIDAR SI ES ESTOY EN PRODUCCION
    $isProduction = isset($_ENV['FLY_PUBLIC_IP']);
    $baseDir = realpath(__DIR__.DIRECTORY_SEPARATOR.'..');

    if(!$isProduction) {
        require_once $baseDir.'/vendor/autoload.php';
        Dotenv\Dotenv::createImmutable($baseDir)->load();
    }

    // ESTABLECER LA CONEXION, SESSION Y TIPO DE CARACTERES 
    $db = mysqli_init();
    
    mysqli_ssl_set($db, null, null, $_ENV['DB_SSL_CA_PATH'], null, null);
    mysqli_real_connect($db, $_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);  
    
    mysqli_query($db, 'SET NAMES "utf8"');

    if(!$db) {
        die('Error en la conexion: '.mysqli_connect_error());
    }

    if(!isset($_SESSION)) {
        session_start();
    }
?>