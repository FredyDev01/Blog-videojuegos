<?php
    // VALIDAR LA SESSION DEL USUARIO Y OBTENER SUS DATOS
    $showMyEntries = ($view === 'mis-entradas');
    $userId = $showMyEntries ? $currentUser->id : null;

    // OBTENER DATO DE LA BUSQUEDA
    $search = getNotEmptyElementArray($_GET, 'busqueda', '');
?>

<!--CAJA PRINCIPAL-->
<div id="main" class="flex-col gap-lg section">
    <h1 class="title"><?= $showMyEntries ? 'Mis entradas' : 'Entradas generales' ?></h1>
    <div class="flex-col gap-lg filters">        
        <div class="container-search">
            <input type="text" class="form-input" id="search" value="<?= $search ?>" placeholder="Buscar entrada">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <?php
            $categories = getCategories(true);
            $data = $categories['data'];        
            if(mysqli_num_rows($data)):
        ?>
                <div id="list-categories" class="flex-row gap-xxs">
                    <?php   
                        $filters = isset($_GET['categoria']) ? $_GET['categoria'] : [];
                        while($category = mysqli_fetch_object($data)):
                            $isActive = in_array($category->nombre, $filters);
                    ?>
                        <label
                            class="btn btnSm roundedLg btnOutlinePrimary <?= $isActive ? 'active' : '' ?>" 
                            for="filter-<?= $category->nombre ?>"
                        >
                            <input 
                                type="checkbox"
                                name="categoria[]" 
                                id="filter-<?= $category->nombre ?>" 
                                value="<?= $category->nombre ?>"
                                <?= $isActive ? 'checked' : '' ?>
                            >                            
                                <?= $category->nombre ?>
                        </label>
                    <?php
                        endwhile; 
                    ?>
                    <button class="btn btnSm btnPrimary roundedLg">
                        <i class="fa-solid fa-filter"></i> Filtrar
                    </button>                        
                </div>        
        <?php
            endif;       
        ?>
    </div>
    <?php 
        $categories = getNotEmptyElementArray($_GET, 'categoria', []);
        $currentPage = intval(getIssetElementArray($_GET, 'pagina', 1));
        $entries = getEntries($userId, $search, $categories, $currentPage);
        $data = $entries['data'];
        $maxPage = $entries['maxPage'];
        if(mysqli_num_rows($data)):
            while($entry = mysqli_fetch_object($data)):
    ?>
                <article>
                    <a href="entrada?id=<?= $entry->id ?>" class="flex-col gap-xs">
                        <h2 class="subtitle"><?= $entry->titulo ?></h2>
                        <span class="moreInfo"><?= $entry->categorias_nombre.' | '.$entry->fecha ?></span>
                        <p class="content">
                            <?php
                                $description = trim($entry->descripcion);   
                                $maxCaracter = 190;
                                if(strlen($description) > $maxCaracter) {
                                    echo trim(substr($entry->descripcion, 0, 190)).'...';
                                }else {
                                    echo $description;
                                } 
                            ?>
                        </p>
                    </a>
                </article>
    <?php 
            endwhile;
        else:
    ?>
        <div class="alert alert-info">
            Al parecer no tiene ninguna entrada registrada con los filtros seleccionados,
            <?=
                $currentUser 
                ? 'le recomendamos <a href="formulario-entrada">crear una nueva entrada.</a>'
                : 'le recomendamos iniciar sesion y crear una nueva categoria.'
            ?>
        </div>
    <?php
        endif;
        require_once 'includes/pagination.php';
    ?>
</div>
