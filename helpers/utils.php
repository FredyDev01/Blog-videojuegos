<?php
    // RETORNAR UN COTENEDOR CON EL ERROR EN CASO EXISTA
    function showErrors($fieldError, $error) {
        $alert = '';
        if(
            isset($fieldError[$error]) &&
            !empty($fieldError[$error])
        ) {
            $alert = '<div class="alert alert-error">'.$fieldError[$error].'</div>';
        }
        return $alert;
    }

    // RETORNAR UN COTENEDOR CON EL EXITO EN CASO EXISTA
    function showSuccess($fieldSuccess, $success) {        
        $alert = '';
        if(
            isset($fieldSuccess[$success]) && 
            !empty($fieldSuccess[$success])
        ) {
            $alert = '<div class="alert alert-success">'.$fieldSuccess[$success].'</div>';
        }
        return $alert;
    }

    // VERIFICA SI SE ELIMINA UN CAMPO EN GENRAL O ESPECIFICO DE LA SESSION
    function clearInfoSession($fieldsDelSession) {        
        if(!empty($fieldsDelSession)) {
            unset($_SESSION[$fieldsDelSession]);
        }
    }

    // OBTENER UN ELEMENTOS EN BASE A ARREGLOS
    function getIssetElementArray($array, $key, $defaultResult) {
        return isset($array[$key]) ? $array[$key] : $defaultResult;
    }

    function getNotEmptyElementArray($array, $key, $defaultResult) {
        return !empty($array[$key]) ? $array[$key] : $defaultResult;
    }

    function getIntElementArray($array, $key) {
        return !empty($array[$key]) ? filter_var($array[$key], FILTER_VALIDATE_INT) : null;
    }

    function getCleanTextElementArray($array, $key) {
        global $db;
        return !empty($array[$key]) ? mysqli_real_escape_string($db, $array[$key]) : null;
    }

    // RETORNAR VALORES VALIDOS
    function getIntValid($value) {
        return !empty($value) ? filter_var($value, FILTER_VALIDATE_INT) : null;
    }

    function getCleanTextValid($value) {
        global $db;
        return !empty($value) ? mysqli_real_escape_string($db, $value) : null;
    }

    // AGREGAR PARAMETROS EN LA URL
    function addParameter($url, $nameParameter, $paramter) {
        $finalURL = '';
        if(empty($url)) {
            $url = $_SERVER['REQUEST_URI'];
        }
        if(!strpos($url, $nameParameter)) {
            $simbol = strpos($url, '?') ? '&' : '?';
            $finalURL = $url.$simbol.$nameParameter.'='.$paramter;
        }else {
            $finalURL = preg_replace("/$nameParameter=\w*/", $nameParameter.'='.$paramter, $url);
        }
        return $finalURL;
    }

    // OBTENER UN LISTADO DE LAS CATEGORIAS
    function getCategories($showAll = null, $search = null, $currentPage = 1, $perPage = 5) {
        global $db;
        $selectData = 'SELECT * ';
        $selectTotal = 'SELECT COUNT(*) AS total_categorias ';
        $sql = 'FROM proyecto_blog_categorias ';
        $categories = [];
        $maxPage = 0;
        if($showAll) {
            $sql .= 'ORDER BY id ASC ';
            $categories = mysqli_query($db, $selectData.$sql);
        }else {
            $search = getCleanTextValid($search);
            $currentPage = getIntValid($currentPage) ?? 1;
            $perPage = getIntValid($perPage) ?? 5; 
            if($search) {
                $sql .= "WHERE nombre LIKE '%$search%' ";
            }
            $totalCategories = mysqli_query($db, $selectTotal.$sql);
            $totalCategories = $totalCategories ? intval(mysqli_fetch_object($totalCategories)->total_categorias) : 0;
            $maxPage = intval(ceil($totalCategories / $perPage));
            if($maxPage && $maxPage < $currentPage) {
                header('Location: '.addParameter($_SERVER['REQUEST_URI'], 'page', $maxPage));
                exit;
            }else if($currentPage < 1) {
                header('Location: '.addParameter($_SERVER['REQUEST_URI'], 'page', 1));
                exit;
            }
            $start = ($currentPage - 1) * $perPage;           
            $sql .= 'ORDER BY id DESC '. 
                    "LIMIT $perPage OFFSET $start";
            $categories = mysqli_query($db, $selectData.$sql);
        }
        return [
            'data' => $categories,
            'maxPage' => $maxPage
        ];        
    }

    // OBTENER UNA CATEGORIA EN ESPECIFICO
    function getCategory($id) {
        global $db;
        $id = getIntValid($id);
        if(!$id) return false;
        $sql = "SELECT * FROM proyecto_blog_categorias WHERE id = $id";
        $category = mysqli_query($db, $sql);
        $result = false;
        if($category && mysqli_num_rows($category) >= 1) {
            $result = mysqli_fetch_object($category);
            $result = empty($result->id) ? false : $result;
        }
        return $result;
    }

    // OBTENER UN LISTADO DE LAS ENTRADAS
    function getEntries($userId = null, $search = null, $categories = null, $currentPage = 1, $perPage = 5) {
        global $db;
        $selectData = 'SELECT e.*, GROUP_CONCAT(c.nombre SEPARATOR ", ") AS categorias_nombre ';
        $selectTotal = 'SELECT COUNT(DISTINCT e.id) AS total_entradas ';
        $sql = 'FROM proyecto_blog_entradas_categorias ec '.
               'INNER JOIN proyecto_blog_entradas e ON(ec.entrada_id = e.id) '.
               'INNER JOIN proyecto_blog_categorias c ON(ec.categoria_id = c.id) ';        
        $userId = getIntValid($userId);
        $search = getCleanTextValid($search);
        $currentPage = getIntValid($currentPage) ?? 1;
        $perPage = getIntValid($perPage) ?? 5;
        if(!empty($userId)) {            
            $sql .= "WHERE e.usuario_id = $userId ";
        }
        if(!empty($search)) {
            $sql .= strpos($sql, 'WHERE') ? 'AND ' : 'WHERE ';
            $sql .= "e.titulo LIKE '%$search%' ";
        }
        if(!empty($categories)) {
            $strCategory = array_map(fn($element) => '"'.getCleanTextValid($element).'"', $categories);
            $sql .= strpos($sql, 'WHERE') ? 'AND ' : 'WHERE ';
            $sql .= count($strCategory).' = ( '.
                        'SELECT COUNT(*) FROM proyecto_blog_entradas_categorias ec2 '.
                        'INNER JOIN proyecto_blog_categorias c2 ON(ec2.categoria_id = c2.id) '.
                        'WHERE ec2.entrada_id = ec.entrada_id AND c2.nombre IN('.implode(', ', $strCategory).') '.
                        'GROUP BY ec2.entrada_id '.
                    ') ';
        }
        $totalEntries = mysqli_query($db, $selectTotal.$sql);
        $totalEntries = $totalEntries ? intval(mysqli_fetch_object($totalEntries)->total_entradas) : 0;        
        $maxPage = intval(ceil($totalEntries / $perPage));
        if($maxPage && $maxPage < $currentPage) {
            header('Location: '.addParameter($_SERVER['REQUEST_URI'], 'page', $maxPage));
            exit;
        }else if($currentPage < 1) {
            header('Location: '.addParameter($_SERVER['REQUEST_URI'], 'page', 1));
            exit;
        }
        $start = ($currentPage - 1) * $perPage;
        $sql .= 'GROUP BY ec.entrada_id '.
                'ORDER BY e.id DESC '.
                "LIMIT $perPage OFFSET $start";
        $entries = mysqli_query($db, $selectData.$sql);
        return [
            'data' => $entries,
            'maxPage' => $maxPage
        ];
    }

    // OBTENER UNA ENTRADA EN ESPECIFICO
    function getEntry($entryId, $userId) {
        global $db;
        $entryId = getIntValid($entryId);
        $userId = getIntValid($userId);
        if(!$entryId) return false;
        $sql = 'SELECT e.*, CONCAT(u.nombre, " ", u.apellidos) AS usuario, '.
               'GROUP_CONCAT(c.nombre SEPARATOR ", ") AS categorias_nombre, '.
               'GROUP_CONCAT(c.id SEPARATOR ", ") AS categorias_id '.
               'FROM proyecto_blog_entradas_categorias ec '.
               'INNER JOIN proyecto_blog_entradas e ON(ec.entrada_id = e.id) '.
               'INNER JOIN proyecto_blog_usuarios u ON(e.usuario_id = u.id) '.
               'INNER JOIN proyecto_blog_categorias c ON(ec.categoria_id = c.id) '.               
               "WHERE e.id = $entryId ";
        if($userId) {
            $sql .= "AND e.usuario_id = $userId";
        }
        $entry = mysqli_query($db, $sql);
        $result = false;
        if($entry && mysqli_num_rows($entry)) {
            $result = mysqli_fetch_object($entry);
            $result = empty($result->id) ? false : $result;
        }
        return $result;
    }

    // OBTENER UN LISTADO DE LOS USUARIOS
    function getUsers($search = null, $currentPage = 1, $perPage = 5) {
        global $db;
        $selectData = 'SELECT * ';
        $selectTotal = 'SELECT COUNT(*) AS total_usuarios ';
        $sql = 'FROM proyecto_blog_usuarios WHERE rol_id IN(1, 2) ';
        $search = getCleanTextValid($search);
        $currentPage = getIntValid($currentPage) ?? 1;
        $perPage = getIntValid($perPage) ?? 5;
        if($search) {
            $sql .= "AND nombre LIKE '%$search%' OR ".
                    "apellidos LIKE '%$search%' OR ".
                    "email LIKE '%$search%' ";
        }
        $totalUsers = mysqli_query($db, $selectTotal.$sql);
        $totalUsers = $totalUsers ? intval(mysqli_fetch_object($totalUsers)->total_usuarios) : 0;        
        $maxPage = intval(ceil($totalUsers / $perPage));
        if($maxPage && $maxPage < $currentPage) {
            header('Location: '.addParameter($_SERVER['REQUEST_URI'], 'page', $maxPage));
            exit;
        }else if($currentPage < 1) {
            header('Location: '.addParameter($_SERVER['REQUEST_URI'], 'page', 1));
            exit;
        }
        $start = ($currentPage - 1) * $perPage;
        $sql .= 'ORDER BY id DESC '.
                "LIMIT $perPage OFFSET $start";
        $entries = mysqli_query($db, $selectData.$sql);
        return [
            'data' => $entries,
            'maxPage' => $maxPage
        ];
    }
?>