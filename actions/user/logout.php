<?php
    require_once '../../helpers/utils.php';

    // INFORMACION PARA LA REDIRRECION
    $originPage = getNotEmptyElementArray($_SERVER, 'HTTP_REFERER', '../../inicio');

    // ELIMINA LA INFORMACION DEL USUARIO EN CASO EXISTA
    session_start();
    if(isset($_SESSION['user'])) {
        session_destroy();
    }

    header("Location: $originPage");
?>