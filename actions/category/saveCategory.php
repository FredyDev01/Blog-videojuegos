<?php
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    // INFORMACION RELACIONADA AL FORMULARIO DE CATEGORIA
    $categoryInfo = array(
        'fields' => array(),
        'errors' => array(),
        'success' => false
    );

    // INFORMACION RELACIONADA A LA EDICION Y REDIRECCION
    $categoryId = getCleanTextElementArray($_GET, 'id');    
    $redirect = getNotEmptyElementArray($_COOKIE, 'redirectCategories', '../../gestionar-categorias');
    $user = getNotEmptyElementArray($_SESSION, 'user', null);
    $userRol = $user ? intval($user->rol_id) : null;    

    $nameParamter = 'updateCategory';

    // LIMPIANDO EL TEXTO OBTENIDO
    $name = getCleanTextElementArray($_POST, 'name');

    // VALIDANDO LAS ENTRADAS CON REGEX
    if(!preg_match('/^[A-Z][\p{L}]+(\s[\p{L}]+){0,3}$/', $name)) {
        $categoryInfo['errors']['name'] = 'El nombre de la categoria no es valido.';
    }

    // PREPARACION Y EJECUCION DE LA CONSULTA EN CASO DE VALIDAR LOS CAMPOS
    if(empty($categoryInfo['errors']) && $userRol >= 2) {
        try {
            if($categoryId) {
                $sql = "UPDATE proyecto_blog_categorias SET nombre = '$name' WHERE id = $categoryId";
                $update = mysqli_query($db, $sql);
                if(!$update) {
                    throw new Exception(mysqli_error($db));
                }
                header('Location: '.addParameter($redirect, $nameParamter, '1'));
                exit;
            }else {
                $sql = "INSERT INTO proyecto_blog_categorias VALUES(null, '$name')";
                $save = mysqli_query($db, $sql);
                if(!$save) {
                    throw new Exception(mysqli_error($db));
                }
                $categoryInfo['success'] = 'La categoria se registro con exito.';                
            }
        }catch(Exception $err) {
            $categoryInfo['errors']['general'] = 'Error al '.($categoryId ? 'actualizar' : 'guardar') .' la categoria.';            
        }
    }

    // DEVOLVER LOS CAMPOS EN CASO EXISTA ALGUN ERROR
    if(!empty($categoryInfo['errors'])) {
        $categoryInfo['fields'] = $_POST;
    }

    // GUARDAR LOS DATOS DEL FORMULARIO EN LA SESSION
    $_SESSION['categoryInfo'] = $categoryInfo;

    // REDIRIGIENDO AL USUARIO A LA PAGINA PRINCIPAL
    header('Location: ../../formulario-categoria'.($categoryId ? "?id=$categoryId" : ''));
?>