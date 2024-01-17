<?php
    require_once '../../helpers/conexion.php';
    
    // INFORMACION RELACIONADA AL FORMULARIO DE ACTUALIZACION DEL USUARIO
    $updateUserInfo = array(
        'fields' => array(),
        'errors' => array(),
        'success' => false        
    );

    // LIMPIANDO EL TEXTO OBTENIDO
    $name = isset($_POST['name']) 
            ? mysqli_real_escape_string($db, trim($_POST['name'])) 
            : false;
    $lastName = isset($_POST['lastName']) 
            ? mysqli_real_escape_string($db, trim($_POST['lastName'])) 
            : false;
    $email = isset($_POST['email']) 
            ? mysqli_real_escape_string($db, trim($_POST['email'])) 
            : false;

    // VALIDANDO LAS ENTRADAS CON REGEX
    if(!preg_match('/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/', $name)) {
        $updateUserInfo['errors']['name'] = 'El nombre no es valido.';
    }
    if(!preg_match('/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/', $lastName)) {
        $updateUserInfo['errors']['lastName'] = 'Los apellidos no son validos.';
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $updateUserInfo['errors']['email'] = 'El email no es valido.';
    }

    // PREPARACION Y EJECUCION DE LA CONSULTA EN CASO DE VALIDAR LAS ENTRADAS
    if(empty($updateUserInfo['errors'])) {
        $user = $_SESSION['user'];
        
        //VERIFICAR QUE EL EMAIL ACTUALIZADO NO EXISTA
        $sql = "SELECT id, email FROM proyecto_blog_usuarios WHERE email = '$email'";
        $issetEmail = mysqli_query($db, $sql);
        $result = mysqli_fetch_object($issetEmail);
        if(empty($result) || $result->id === $user->id) {
            $sql = "UPDATE proyecto_blog_usuarios SET "
                ."nombre = '$name', "
                ."apellidos = '$lastName', "
                ."email = '$email' "
                .'WHERE id = '.$user->id;
            $update = mysqli_query($db, $sql);
            
            // SETEANDO LOS DATOS DATOS DEL FORMULARIO Y MENSAJES DE EXITO O ERROR
            if($update) {
                $_SESSION['user']->nombre = $name;
                $_SESSION['user']->apellidos = $lastName;
                $_SESSION['user']->email = $email;
                $updateUserInfo['success'] = 'Tus datos se han actualizado con exito.';
            }else {
                $updateUserInfo['errors']['general'] = 'Fallo al actualizar tus datos.';
                $updateUserInfo['fields'] = $_POST;
            }            
        }else {
            $updateUserInfo['errors']['email'] = 'El email ya esta siendo usado.';
        }
    }else {
        $updateUserInfo['fields'] = $_POST;
    }

    // DEVOLVER LOS CAMPOS EN CASO EXISTA ALGUN ERROR
    if(!empty($updateUserInfo['errors'])) {
        $dataForm = $_POST;
        $updateUserInfo['fields'] = $dataForm;        
    }

    // GUARDAR LOS DATOS DEL FORMULARIO EN LA SESSION
    $_SESSION['updateUserInfo'] = $updateUserInfo;

    // REDIRIGIENDO AL USUARIO A LA PAGINA DE EDICION DEL USUARIO
    header('Location: ../../mis-datos');
?>