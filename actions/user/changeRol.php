<?php
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    $originPage = getNotEmptyElementArray($_SERVER, 'HTTP_REFERER', '../../gestionar-usuarios');
    $userId = getIntElementArray($_GET, 'id');    
    $userRol = getIntElementArray($_GET, 'rol');
    $currentUser = getNotEmptyElementArray($_SESSION, 'user', null);
    $currentRol = $currentUser ? intval($currentUser->rol_id) : null;

    $nameParameter = 'changeRol';    

    if($userId && $userRol && $currentRol === 3) {                
        $newRol = ($userRol === '2') ? 2 : 1;        
        $sql = "UPDATE proyecto_blog_usuarios SET rol_id = $newRol WHERE id = $userId";
        $update = mysqli_query($db, $sql);
        if($update) {
            header('Location: '.addParameter($originPage, $nameParameter, '1'));
            exit;
        }
    }

    header('Location: '.addParameter($originPage, $nameParameter, '0'));
?>