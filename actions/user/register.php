<?php
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    // INFORMACION RELACIONADA AL FORMULARIO DE REGISTRO
    $originPage = getNotEmptyElementArray($_SERVER, 'HTTP_REFERER', '../../inicio');
    $registerInfo = array(
        'fields' => array(),
        'errors' => array(),
        'success' => false        
    );

    // LIMPIANDO EL TEXTO OBTENIDO
    $regName = isset($_POST['regName']) 
            ? mysqli_real_escape_string($db, trim($_POST['regName'])) 
            : false;
    $regLastName = isset($_POST['regLastName']) 
            ? mysqli_real_escape_string($db, trim($_POST['regLastName'])) 
            : false;
    $regEmail = isset($_POST['regEmail']) 
            ? mysqli_real_escape_string($db, trim($_POST['regEmail'])) 
            : false;
    $regPassword = isset($_POST['regPassword']) 
            ? mysqli_real_escape_string($db, $_POST['regPassword']) 
            : false;

    // VALIDANDO LAS ENTRADAS CON REGEX
    if(!preg_match('/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/', $regName)) {
        $registerInfo['errors']['regName'] = 'El nombre no es valido.';
    }
    if(!preg_match('/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/', $regLastName)) {
        $registerInfo['errors']['regLastName'] = 'Los apellidos no son validos.';
    }
    if(!filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
        $registerInfo['errors']['regEmail'] = 'El email no es valido.';
    }
    if(!preg_match('/^\w{8,}$/', $regPassword)) {
        $registerInfo['errors']['regPassword'] = 'La contraseña no es valida.';
    }

    // PREPARACION Y EJECUCION DE LA CONSULTA EN CASO DE VALIDAR LAS ENTRADAS
    if(empty($registerInfo['errors'])) {
        
        //VERIFICAR QUE EL EMAIL ACTUALIZADO NO EXISTA
        $sql = "SELECT id, email FROM proyecto_blog_usuarios WHERE email = '$regEmail'";
        $issetEmail = mysqli_query($db, $sql);
        $result = mysqli_fetch_object($issetEmail);
        if(empty($result)) {
            $regPassword = password_hash($regPassword, PASSWORD_BCRYPT, ['cost'=> 4]);
            $sql = "INSERT INTO proyecto_blog_usuarios VALUES(null, '$regName', '$regLastName', '$regEmail', '$regPassword', CURDATE(), 1);";
            $save = mysqli_query($db, $sql);
    
            // SETEANDO LOS DATOS DATOS DEL FORMULARIO Y MENSAJES DE EXITO O ERROR
            if($save) {            
                $registerInfo['success'] = 'El registro se a completado con exito.';
            }else {
                $registerInfo['errors']['general'] = 'Fallo al guardar el usuario.';
                $registerInfo['fields'] = $_POST;
            }
        }else {
            $registerInfo['errors']['regEmail'] = 'El email ya esta siendo usado.';
        }
    }else {
        $registerInfo['fields'] = $_POST;
    }

    // DEVOLVER LOS CAMPOS EN CASO EXISTA ALGUN ERROR EXCLUYENDO LA PASSWORD
    if(!empty($registerInfo['errors'])) {
        $dataForm = $_POST;
        if(!empty($regPassword)) {
            unset($dataForm['regPassword']);
        }
        $registerInfo['fields'] = $dataForm;        
    }

    // GUARDAR LOS DATOS DEL FORMULARIO EN LA SESSION
    $_SESSION['registerInfo'] = $registerInfo;

    // REDIRIGIENDO AL USUARIO A LA PAGINA PRINCIPAL
    header("Location: $originPage");
?>