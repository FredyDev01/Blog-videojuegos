<?php
    require_once 'helpers/utils.php';
    require_once 'helpers/conexion.php';

    // MANEJO DE LAS RUTAS
    const VIEWS = [
        'inicio' => [
            'render' => 'entries',
            'min-rol' => 0
        ],
        'mis-entradas' => [
            'render' => 'entries',
            'min-rol' => 1
        ],
        'entrada' => [
            'render' => 'entry',
            'min-rol' => 0
        ],
        'mis-datos' => [
            'render' => 'formUser',
            'min-rol' => 1
        ],
        'gestionar-categorias' => [
            'render' => 'manageCategories',
            'min-rol' => 2
        ],
        'gestionar-usuarios' => [
            'render' => 'manageUsers',
            'min-rol' => 3
        ],
        'formulario-categoria' => [
            'render' => 'formCategory',
            'min-rol' => 2
        ],
        'formulario-entrada' => [
            'render' => 'formEntry',
            'min-rol' => 1
        ],
    ];

    // RECOLECTAR INFORMACION UTIL PARA LA PAGINA
    $view = $_GET['view'];
    $title = ucfirst(preg_replace('/-/', ' ', $view));
    $infoView = getElementArray(VIEWS, $view, []);        
    $render = getElementArray($infoView, 'render', false);
    $minRol = getElementArray($infoView, 'min-rol', false);    
    $currentUser = getElementArray($_SESSION, 'user', null);
    $currentRol = $currentUser ? intval($currentUser->rol_id) : 0;  

    // REDIRECCIONAR EN CASO NO SE TENGA EL ROL NECESARIO
    if(
        ($render === false || $minRol === false) || 
        ($minRol > $currentRol)
    ) {
        header('Location: inicio');
        exit;
    }    

    // RENDERIZAR FINAL DE LA PAGINA
    require_once 'includes/header.php';
    require_once 'includes/changeSections.php';
    require_once 'views/'.$render.'.php';
    require_once 'includes/aside.php';
    require_once 'includes/footer.php';
?>