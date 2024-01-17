<?php 
    require_once 'helpers/utils.php';

    //DATOS DEL FORMULARIO DE EDITAR PERFIL
    $sessionUpdateUser = getElementArray($_SESSION, 'updateUserInfo', []);
    $errors = getValidElementArray($sessionUpdateUser, 'errors', []);
    
    $name = $_SESSION['user']->nombre;
    $lastName = $_SESSION['user']->apellidos;
    $email = $_SESSION['user']->email;

    if(!empty($sessionUpdateUser['fields'])) {
        $fieldsUpdateUser = $sessionUpdateUser['fields']; 
        $name = $fieldsUpdateUser['name'];
        $lastName = $fieldsUpdateUser['lastName'];
        $email = $fieldsUpdateUser['email'];
    }
?>

<div id="main" class="flex-col gap-lg section">
    <div class="infoDataMain">
        <h1 class="title">Actualizar datos</h1>
        <p>
            Cambia tus datos personales para corregir algun error y visualizar los nuevos valores 
            dentro de la pagina.
        </p>
    </div>
    <form action="actions/user/updateUser.php" method="POST" class="flex-col gap-lg">
        <div>
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name" class="form-input" value="<?= $name ?>">
            <?= showErrors($errors, 'name') ?>
        </div>
        <div>
            <label for="lastName">Apellidos: </label>
            <input type="text" name="lastName" id="lastName" class="form-input" value="<?= $lastName ?>">
            <?= showErrors($errors, 'lastName') ?>
        </div>
        <div>
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" class="form-input" value="<?= $email ?>">
            <?= showErrors($errors, 'email') ?>
        </div>
        <?= showSuccess($sessionUpdateUser, 'success') ?>
        <?= showErrors($errors, 'general') ?>
        <button type="submit" class="mx-auto btn btnLg roundedLg btnPrimary">
            Editar datos
        </button>
    </form>
</div>

<?php
    require_once 'includes/aside.php';
    require_once 'includes/footer.php';

    // ELIMINAR LA INFORMACION INNECESARIA DE EDITAR PERFIL
    clearInfoSession('updateUserInfo');
?>