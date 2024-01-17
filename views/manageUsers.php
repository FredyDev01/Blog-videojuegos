<?php
    require_once 'helpers/utils.php';

    // OBTENER DATO DE LA BUSQUEDA
    $search = getNotEmptyElementArray($_GET, 'busqueda', '');      
?>

<main id="main" class="flex-col gap-lg section">
    <div class="infoDataMain">
        <h1 class="title">Gestionar usuarios</h1>
        <p>
            Administra a todos los usuarios registrados dentro del sitio, con el fin de regular sus roles.
        </p>
    </div>
    <div class="filters">
        <div class="container-search">
            <input type="text" class="form-input" id="search" value="<?= $search ?>" placeholder="Buscar usuario">
            <i class="fa-solid fa-magnifying-glass"></i>            
        </div>        
    </div>    
    <?php
        $currentPage = intval(getIssetElementArray($_GET, 'pagina', 1));
        $users = getUsers(search: $search, currentPage: $currentPage);
        $data = $users['data'];
        $maxPage = $users['maxPage'];
        if(mysqli_num_rows($data)):
    ?>
            <div class="mx-auto table-main">
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Administrador</th>
                        </tr>
                    </thead>
                    <tbody id="infoTable">            
                        <?php             
                            while($user = mysqli_fetch_object($data)):
                        ?>            
                                <tr>
                                    <td><?= $user->id ?></td>
                                    <td><?= $user->nombre ?></td>
                                    <td><?= $user->apellidos ?></td>
                                    <td><?= $user->email ?></td>
                                    <td class="flex-row">
                                        <input                                        
                                            type="checkbox"
                                            data-id="<?= $user->id ?>"
                                            <?= $user->rol_id === '2' ? 'checked' : ''?>
                                        >
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
                Al parecer no tiene ningun usuario registrada con los filtros seleccionados,
                le recomendamos registrar a ese nuevo usuario
            </div>
    <?php
        endif;
        require_once 'includes/pagination.php';
    ?>
</main>