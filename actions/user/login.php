<?php 
    require_once '../../helpers/conexion.php';
    require_once '../../helpers/utils.php';

    // INFORMACION RELACIONADA AL FORMULARIO Y REDIRECCION
    $originPage = getNotEmptyElementArray($_SERVER, 'HTTP_REFERER', '../../inicio');
    $loginInfo = array(
        'fields' => array(),
        'error' => false
    );

    // LIMPIANDO EL TEXTO OBTENIDO
    $logEmail = getCleanTextElementArray($_POST, 'logEmail');
    $logPassword = getCleanTextElementArray($_POST, 'logPassword');

    // PREPARACION Y EJECUCION DE LA CONSULTA
    $sql = "SELECT * FROM proyecto_blog_usuarios WHERE email = '$logEmail' LIMIT 1";
    $login = mysqli_query($db, $sql);

    // SETEANDO LOS DATOS DEL USUARIO Y LOS ERRORES
    if($login && mysqli_num_rows($login) === 1) {
        $user = mysqli_fetch_object($login);    
        if(password_verify($logPassword, $user->password)) {            
            $_SESSION['user'] = $user;
        }else {
            $loginInfo['error'] = 'El email o la contraseña son incorrectos.';
        }
    }else{
        $loginInfo['error'] = 'El email o la contraseña son incorrectos.';
    }

    // DEVOLVER LOS CAMPOS EN CASO EXISTA ALGUN ERROR EXCLUYENDO LA PASSWORD
    if(!empty($loginInfo['error'])) {
        $dataForm = $_POST;
        if(!empty($logPassword)) {
            unset($dataForm['logPassword']);
        }
        $loginInfo['fields'] = $dataForm;
    }

    // GUARDAR LOS DATOS DEL FORMULARIO EN LA SESSION
    $_SESSION['loginInfo'] = $loginInfo;

    // REDIRIGIENDO AL USUARIO A LA PAGINA PRINCIPAL
    header("Location: $originPage");
?>