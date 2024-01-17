<?php
    require_once '../../helpers/utils.php';

    // INFORMACION PARA LA REDIRRECION
    $originPage = getElementArray($_SERVER, 'HTTP_REFERER', null);

    $originPage || header('Location: ../index.php');

    // ELIMINA LA INFORMACION DEL USUARIO EN CASO EXISTA
    session_start();
    
    if(isset($_SESSION['user'])) {
        session_destroy();
    }

    header("Location: $originPage");
?>