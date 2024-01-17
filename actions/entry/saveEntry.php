<?php
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    // INFORMACION RELACIONADA AL FORMULARIO
    $entryInfo = array(
        'fields' => array(),
        'errors' => array(),
        'success' => false
    );

    // INFORMACION RELACIONADA A LA EDICION
    $entryId = getCleanTextElementArray($_GET, 'id');    
    $redirect = "../../entrada?id=$entryId";
    $user = getNotEmptyElementArray($_SESSION, 'user', null);
    $userId = $user ? $_SESSION['user']->id : null;
    
    $nameParamter = 'updateEntry';

    // LIMPIANDO EL TEXTO OBTENIDO
    $title =  getCleanTextElementArray($_POST, 'title');
    $category = getNotEmptyElementArray($_POST, 'category', []);
    $description = getCleanTextElementArray($_POST, 'description');

    // VALIDANDO LAS ENTRADAS CON REGEX
    if(!preg_match('/^[A-Z][\p{L}]+(\s[\p{L}\d]+){0,50}$/', $title)) {
        $entryInfo['errors']['title'] = 'El titulo de la entrada no es valido.';
    }
    if(empty($category)) {
        $entryInfo['errors']['category'] = 'Debe seleccionar almenos una categoria.';
    }
    if(!preg_match('/^[A-Z].+(\s.+){0,2000}$/', $description)) {
        $entryInfo['errors']['description'] = 'La descripcion de la entrada no es valida.';
    }

    // PREPARACION Y EJECUCION DE LA CONSULTA EN CASO DE VALIDAR LOS CAMPOS
    if(empty($entryInfo['errors']) && !empty($_SESSION['user']->id)) {
        $userId = $_SESSION['user']->id;
        mysqli_begin_transaction($db);
        try {                     
            if($entryId) {
                $sql = "UPDATE proyecto_blog_entradas SET titulo = '$title', descripcion = '$description' WHERE id = $entryId AND usuario_id = $userId";
                $update = mysqli_query($db, $sql);
                if(!$update) {
                    throw new Exception(mysqli_error($db));
                }
                $sql = "SELECT * FROM proyecto_blog_entradas_categorias WHERE entrada_id = $entryId";
                $categoriesEntries = mysqli_query($db, $sql);
                while($categoryEntry = mysqli_fetch_object($categoriesEntries)) {
                    $indCategory = array_search($categoryEntry->categoria_id, $category);        
                    if($indCategory) {
                        unset($category[$indCategory]);
                    }else {
                        $sql = "DELETE FROM proyecto_blog_entradas_categorias WHERE id = $categoryEntry->id";
                        $delete = mysqli_query($db, $sql);
                        if(!$delete) {
                            throw new Exception(mysqli_error($db));
                        }
                    }
                }
                foreach($category as $categoryId) {
                    $sql = "INSERT INTO proyecto_blog_entradas_categorias VALUES(null, $categoryId, $entryId)";
                    $save = mysqli_query($db, $sql);
                    if(!$save) {
                        throw new Exception(mysqli_error($db));
                    }
                }
                mysqli_commit($db);
                header('Location: '.addParameter($redirect, $nameParamter, '1'));
                exit;
            }else {
                $sql = "INSERT INTO proyecto_blog_entradas VALUES(null, $userId, '$title', '$description', CURDATE())";
                $save = mysqli_query($db, $sql);
                if(!$save) {
                    throw new Exception(mysqli_error($db));
                }
                $newEntryId = mysqli_insert_id($db);
                foreach($category as $idCategory) {
                    $id = mysqli_real_escape_string($db, $idCategory);
                    $sql = "INSERT INTO proyecto_blog_entradas_categorias VALUES(null, $id, $newEntryId)";
                    $save = mysqli_query($db, $sql);
                    if(!$save) {                        
                        throw new Exception(mysqli_error($db));
                    }
                }
                mysqli_commit($db);
                $entryInfo['success'] = 'La entrada se registro con exito.';
            }
        }catch(Exception $err) {
            mysqli_rollback($db);
            $entryInfo['errors']['general'] = 'Error al '.($entryId ? 'actualizar' : 'guardar').' la entrada.';            
        }
    }

    // DEVOLVER LOS CAMPOS EN CASO EXISTA ALGUN ERROR
    if(!empty($entryInfo['errors'])) {
        $entryInfo['fields'] = $_POST;
    }

    // GUARDAR LOS DATOS DEL FORMULARIO EN LA SESSION
    $_SESSION['entryInfo'] = $entryInfo;

    // REDIRIGIENDO AL USUARIO A LA PAGINA QUE REALIZO LA PETICION
    header("Location: ../../formulario-entrada".($entryId ? "?id=$entryId" : ''));
?>