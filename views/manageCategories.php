<?php
    require_once 'helpers/utils.php';

    // OBTENER DATO DE LA BUSQUEDA
    $search = getNotEmptyElementArray($_GET, 'busqueda', '');
?>

<main id="main" class="flex-col gap-lg section">
    <div class="infoDataMain">
        <h1 class="title">Gestionar categorias</h1>
        <p>
            Administra todas las categorias registradas dentro del sitio, a fin de mejorar
            la experiencia del usuario.
        </p>
    </div>
    <div class="filters">
        <div class="container-search">
            <input type="text" class="form-input" id="search" value="<?= $search ?>" placeholder="Buscar categoria">
            <i class="fa-solid fa-magnifying-glass"></i>            
        </div>        
    </div>
    <?php
        $currentPage = intval(getIssetElementArray($_GET, 'pagina', 1));
        $categories = getCategories(search: $search, currentPage: $currentPage);
        $data = $categories['data'];
        $maxPage = $categories['maxPage'];
        if(mysqli_num_rows($data)):
    ?>
            <div class="mx-auto table-main">
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="infoTable">            
                        <?php             
                            while($category = mysqli_fetch_object($data)):
                        ?>            
                                <tr>
                                    <td><?= $category->id ?></td>
                                    <td><?= $category->nombre ?></td>
                                    <td class="flex-row gap-xxs">
                                        <a 
                                            class="btn btnXs roundedXs btnOutlinePrimary" 
                                            href="formulario-categoria?id=<?= $category->id ?>"
                                        >
                                            <i class="fa-solid fa-pencil"></i>
                                            Editar
                                        </a>
                                        <button 
                                            class="btn btnXs roundedXs btnOutlineDanger"
                                            data-id="<?= $category->id ?>" 
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        ?>
                    </tbody>
                </table>
            </div>                 
    <?php
        else:
    ?>
            <div class="alert alert-info">
                Al parecer no tiene ninguna categoria registrada con los filtros seleccionados,
                le recomendamos <a href="formulario-categoria">crear una nueva categoria.</a>
            </div>
    <?php
        endif;
        require_once 'includes/pagination.php';
    ?>
</main>