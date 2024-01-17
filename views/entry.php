<?php
    // REDIRECCIONAR EN CASO DE NO TENER ID
    $entry = getEntry($_GET['id'], null);    

    if(!$entry) {
        header("Location: inicio");
    }

    $nameCategories = explode(',', $entry->categorias_nombre);
?>

<!--CAJA PRINCIPAL-->
<div id="main" class="flex-col gap-lg section">
    <h1 class="title"><?= $entry->titulo ?></h1>
    <div class="flex-col gap-sm">
        <div class="flex-row gap-xxs">
            <?php foreach($nameCategories as $nameCategory): ?>
                    <a href=
                        "inicio?categoria%5B%5D=<?= $nameCategory ?>" 
                        class="btn btnXs roundedLg btnOutlinePrimary"
                    >
                        <?= $nameCategory ?>
                    </a>
            <?php endforeach ?>
        </div>
        <span class="moreInfo"><?= $entry->usuario .' | '. $entry->fecha ?></span>
        <p class="content"><?= $entry->descripcion ?></p>
    </div>
    <?php
        if($currentUser && $currentUser->id === $entry->usuario_id):
    ?>
            <div class="ctn-buttons mx-auto flex-row gap-sm">
                <a 
                    href="formulario-entrada?id=<?= $entry->id ?>" 
                    class="btn btnLg roundedLg btnPrimary"
                >
                    <i class="fa-solid fa-pencil"></i>
                    Editar entrada
                </a>
                <button 
                    id="btnDeleteEntry"
                    class="btn btnLg roundedLg btnDanger" 
                    data-id="<?= $entry->id ?>" 
                >
                    <i class="fa-solid fa-trash"></i>
                    Eliminar entrada
                </button>
            </div>
    <?php 
        endif;
    ?>
</div>