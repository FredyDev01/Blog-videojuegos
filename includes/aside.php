<?php
    // DATOS DEL LOGIN
    $sessionLogin = getIssetElementArray($_SESSION, 'loginInfo', []);

    $logEmail = '';

    if(!empty($sessionLogin['fields'])) {
        $logEmail = $sessionLogin['fields']['logEmail'];
    }

    // DATOS DEL REGISTRO
    $sessionRegister = getIssetElementArray($_SESSION, 'registerInfo', []);
    $errors = getNotEmptyElementArray($sessionRegister, 'errors', []);

    $regName = '';
    $regLastName = '';
    $regEmail = '';    

    if(!empty($sessionRegister['fields'])) {
        $fieldsRegister = $sessionRegister['fields']; 
        $regName = $fieldsRegister['regName'];
        $regLastName = $fieldsRegister['regLastName'];
        $regEmail = $fieldsRegister['regEmail'];
    }
?>

<!--BARRA LATERAL-->
<aside id="sidebar">
    <?php 
        if($currentUser): 
    ?>
            <div id="actions" class="block-aside section">
                <div class="container-profile">
                    <img src="assets/images/userProfile.jpg" alt="Default user profile">
                </div>
                <p>
                    Bienvenido de vuelta, <br> <?= $currentUser->nombre.' '.$currentUser->apellidos ?>
                </p>
                <a href="formulario-entrada" class="btn btnSm roundedLg btnPrimary">
                    <i class="fa-solid fa-file-pen"></i>
                    Crear entradas
                </a>
                <?php 
                    if($currentRol >= 2):
                ?>
                        <a href="formulario-categoria" class="btn btnSm roundedLg btnSuccess">
                            <i class="fa-solid fa-clipboard-list"></i>
                            Crear categoria
                        </a>
                <?php 
                    endif;
                ?>
                <a href="mis-datos" class="btn btnSm roundedLg btnWarning">
                    <i class="fa-solid fa-user"></i>
                    Mis datos
                </a>
                <a href="actions/user/logout.php" class="btn btnSm roundedLg btnDanger">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar sesion
                </a>
            </div>
    <?php 
        else: 
    ?>
            <div id="login" class="block-aside section">
                <h3 class="title">Identificate</h3>
                <form action="actions/user/login.php" method="POST" class="flex-col gap-sm">
                    <div>
                        <label for="logEmail" class="content">Email: </label>
                        <input type="email" name="logEmail" id="logEmail" class="form-input" value="<?= $logEmail ?>">
                    </div>
                    <div>
                        <label for="logPassword" class="content">Contraseña: </label>
                        <input type="password" name="logPassword" id="logPassword" class="form-input">
                    </div>
                    <?= showErrors($sessionLogin, 'error') ?>
                    <button type="submit" class="btn btnSm roundedLg btnPrimary">Entrar</button>
                </form>
            </div>
            <div id="register" class="block-aside section">
                <h3 class="title">Registrate</h3>
                <form action="actions/user/register.php" method="POST" class="flex-col gap-sm">
                    <div>
                        <label for="regName" class="content">Nombre: </label>
                        <input type="text" name="regName" id="regName" class="form-input" value="<?= $regName ?>">
                        <?= showErrors($errors, 'regName') ?>
                    </div>
                    <div>
                        <label for="regLastName" class="content">Apellidos: </label>
                        <input type="text" name="regLastName" id="regLastName" class="form-input" value="<?= $regLastName ?>">
                        <?= showErrors($errors, 'regLastName') ?>
                    </div>
                    <div>
                        <label for="regEmail" class="content">Email: </label>
                        <input type="email" name="regEmail" id="regEmail" class="form-input" value="<?= $regEmail ?>">
                        <?= showErrors($errors, 'regEmail') ?>
                    </div>
                    <div>
                        <label for="regPassword" class="content">Contraseña: </label>
                        <input type="password" name="regPassword" class="form-input" id="regPassword">
                        <?= showErrors($errors, 'regPassword') ?>
                    </div>
                    <?= showSuccess($sessionRegister, 'success') ?>
                    <?= showErrors($errors, 'general') ?>
                    <button type="submit" class="btn btnSm roundedLg btnPrimary">Registrar</button>
                </form>
            </div>
    <?php 
        endif; 
    ?>
</aside>

<?php
    // ELIMINA LA INFORMACION INNECESARIA DEL LOGIN Y REGISTRO
    clearInfoSession('loginInfo');
    clearInfoSession('registerInfo');
?>