<?php
    require_once 'helpers/utils.php';

    // DATOS PARA LA EDICION DE ENTRADAS
    $entry = null;
    if(isset($_GET['id'])) {
        $entry = getEntry($_GET['id'], $currentUser->id);
    }

    //DATOS PARA EL FORMULARIO DE ENTRADAS
    $sessionCategory = getElementArray($_SESSION, 'entryInfo', []);
    $errors = getValidElementArray($sessionCategory, 'errors', []);

    $title = $entry ? $entry->titulo : '';
    $activeCategories = $entry ? explode(',', $entry->categorias_id) : [];
    $description = $entry ? $entry->descripcion : '';

    if(!empty($sessionCategory['fields'])) {
        $title = $sessionCategory['fields']['title'];
        $activeCategories = $sessionCategory['fields']['category'] ?? [];
        $description = $sessionCategory['fields']['description'];
    }
?>

<div id="main" class="flex-col gap-lg section">
    <div class="infoDataMain">
        <h1 class="title">
            <?= $entry ? 'Editar entrada' : 'Crear entrada' ?>            
        </h1>
        <p>
            <?=
                $entry ? '
                    Cambia los datos de la entrada actual para modificar
                    toda la informacion disponible de la misma.
                ' : '
                    Añade nuevas entradas al blog para que los usuarios 
                    puedan leerlas y disfrutar de nuestro contenido.
                '
            ?>
        </p>
    </div>
    <form 
        action=<?= 'actions/entry/saveEntry.php'.($entry ? "?id=$entry->id" : '') ?> 
        method="POST"
        class="flex-col gap-lg"
    >
        <div>
            <label for="title">Titulo: </label>
            <input type="text" name="title" id="title" class="form-input" value="<?= $title ?>">
            <?= showErrors($errors, 'title') ?>
        </div>
        <div>
            <label>Categorias: </label>
            <div class="form-input container-multi-select">
                <?php
                    $categories = getCategories();
                    $data = $categories['data'];
                    $isDataCategories = mysqli_num_rows($data); 
                    if($isDataCategories):
                ?>
                        <span><?= count($activeCategories) ?> categorias seleccionadas</span>
                        <i class="fa-solid fa-chevron-down"></i>
                        <ul>
                            <?php 
                                while($category = mysqli_fetch_object($data)):
                                    $isActive = in_array($category->id, $activeCategories);
                            ?>
                                    <li>                                    
                                        <label 
                                            for="opt-<?= $category->id ?>" 
                                            class="<?= $isActive ? 'active' : '' ?>"
                                        >
                                            <input 
                                                type="checkbox"
                                                name="category[]"
                                                value="<?= $category->id ?>"
                                                id="opt-<?= $category->id ?>"
                                                <?= $isActive ? 'checked' : '' ?> 
                                            >
                                            <?= $category->nombre ?>
                                        </label>
                                    </li>
                            <?php 
                                endwhile;                                
                            ?>             
                        </ul>
                <?php
                    else:
                ?>
                    <span>Sin categorias disponibles</span>
                    <i class="fa-solid fa-chevron-down"></i>
                <?php
                    endif;
                ?>
            </div>
            <?= showErrors($errors, 'category') ?>
        </div>                      
        <div>
            <label for="description">Descripcion: </label>
            <textarea name="description" id="description" class="form-input"><?= $description ?></textarea>
            <?= showErrors($errors, 'description') ?>
        </div>
        <?= showSuccess($sessionCategory, 'success') ?>
        <?= showErrors($errors, 'general') ?>
        <button 
            type="submit" 
            class="mx-auto btn btnLg roundedLg btnPrimary"
            <?= $isDataCategories ? '' : 'disabled' ?>
        >        
            <?= $entry ? 'Actualizar entrada' : 'Guardar entrada' ?>
        </button>
    </form>
</div>

<?php
    require_once 'includes/aside.php';
    require_once 'includes/footer.php';

    // ELIMINAR LA INFORMACION INNECESARIA DE CREAR ENTRADA
    clearInfoSession('entryInfo');
?>