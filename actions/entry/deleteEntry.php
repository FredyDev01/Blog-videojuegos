<?php
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    // INFORMACION NECESARIA PARA LA ELIMINACION Y REDIRECCION    
    $entryId = getElementArray($_GET, 'id', null);
    $redirect = getElementArray($_COOKIE, 'redirectEntries', '../../inicio');
    $user = getElementArray($_SESSION, 'user', null);
    $userId = $user ? $user->id : null;

    $nameParameter = 'deleteEntry';

    // ELIMINANDO LA ENTRADA EN CASO DE ENCONTRAR AL USUARIO Y EL ID
    if($entryId && $userId) {    
        mysqli_begin_transaction($db);        
        try {
            $sql = 'DELETE ec FROM proyecto_blog_entradas_categorias ec '.
                   'INNER JOIN proyecto_blog_entradas e ON(ec.entrada_id = e.id) '.
                   "WHERE ec.entrada_id = $entryId AND e.usuario_id = $userId";            
            $delete = mysqli_query($db, $sql);
            if(!$delete) {
                throw new Exception(mysqli_error($db));
            }            
            $sql = "DELETE FROM proyecto_blog_entradas WHERE id = $entryId AND usuario_id = $userId";
            echo $sql;
            $delete = mysqli_query($db, $sql);
            if(!$delete) {
                throw new Exception(mysqli_error($db));
            }
            mysqli_commit($db);
            header('Location: '.addParameter($redirect, $nameParameter, '1'));
            exit;
        }catch(Exception $err) {
            mysqli_rollback($db);
        }
    }

    // DIRECCIONAR A LA PAGINA DE RETORNO
    header('Location: '.addParameter("../../entrada?id=$entryId", $nameParameter, '0'));
?>