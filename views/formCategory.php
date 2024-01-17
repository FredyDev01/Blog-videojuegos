<?php 
    require_once 'helpers/utils.php';

    // DATOS PARA LA EDICION DE CATEGORIAS
    $category = null;
    if(isset($_GET['id'])) {
        $category = getCategory($_GET['id']);
    }

    //DATOS PARA EL FORMULARIO  DE CATEGORIAS
    $sessionCategory = getElementArray($_SESSION, 'categoryInfo', []);
    $errors = getValidElementArray($sessionCategory, 'errors', []);

    $name = $category ? $category->nombre : '';

    if(!empty($sessionCategory['fields'])) {
        $name = $sessionCategory['fields']['name'];
    }
?>

<div id="main" class="flex-col gap-lg section">
    <div class="infoDataMain">
        <h1 class="title">
            <?= $category ? 'Editar categoria' : 'Crear categoria' ?>
        </h1>
        <p>
            <?= 
                $category ? '
                    Cambia los datos de la categoria actual para modificar
                    toda la informacion disponible de la misma.                    
                ' : '
                    Añade nuevas categorias al blog para que los usuarios puedan usarlas al
                    crear sus entradas.                
                '
            ?>
        </p>
    </div>
    <form
        action=<?= 'actions/category/saveCategory.php'.($category ? "?id=$category->id" : '') ?>
        method="POST"
        class="flex-col gap-lg"
    >
        <div>
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name" class="form-input" value="<?= $name ?>">
            <?= showErrors($errors, 'name') ?>
        </div>
        <?= showSuccess($sessionCategory, 'success') ?>
        <?= showErrors($errors, 'general') ?>
        <button type="submit" class="mx-auto btn btnLg roundedLg btnPrimary">
            <?= $category ? 'Actualizar categoria' : 'Guardar categoria' ?>
        </button>
    </form>
</div>

<?php
    require_once 'includes/aside.php';
    require_once 'includes/footer.php';

    // ELIMINAR LA INFORMACION INNECESARIA DE CREAR CATEGORIA
    clearInfoSession('categoryInfo');
?>