<?php 
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    // INFORMACION NECESARIA PARA LA ELIMINACION Y REDIRECCION    
    $categoryId = getCleanTextElementArray($_GET, 'id');
    $originPage = getNotEmptyElementArray($_SERVER, 'HTTP_REFERER', '../../gestionar-categorias');
    $user = getNotEmptyElementArray($_SESSION, 'user', null);
    $userRol = $user ? intval($user->rol_id) : null;

    $nameParameter = 'deleteCategory';

    if($categoryId && $userRol >= 2) {
        mysqli_begin_transaction($db);
        try {
            $sql = "DELETE FROM proyecto_blog_entradas_categorias WHERE categoria_id = $categoryId";
            $delete = mysqli_query($db, $sql);
            if(!$delete) {
                throw new Exception(mysqli_error($db));
            }
            $sql = 'DELETE FROM proyecto_blog_entradas e WHERE NOT EXISTS( '.
                   'SELECT 1 FROM proyecto_blog_entradas_categorias ec WHERE e.id = ec.entrada_id '.
                   ')';
            $delete = mysqli_query($db, $sql);
            if(!$delete) {
                throw new Exception(mysqli_error($db));
            }
            $sql = "DELETE FROM proyecto_blog_categorias WHERE id = $categoryId";            
            $delete = mysqli_query($db, $sql);
            if(!$delete) {
                throw new Exception(mysqli_error($db));
            }
            mysqli_commit($db);
            header('Location: '.addParameter($originPage, $nameParameter, '1'));
            exit;            
        }catch(Exception $err) {
            die($err);
            mysqli_rollback($db);
        }
    }

    header('Location: '.addParameter($originPage, $nameParameter, '0'));
?>