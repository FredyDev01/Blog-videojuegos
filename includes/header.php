<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog de videojuegos - <?= $title ?></title>
    <link rel="icon" href="assets/images/icon.png">
    <!--ESTILOS PERSONALIZADOS-->    
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <!--FONTAWASOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
    <!--SWEETALERT-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main id="main-container">
        <!--CABECERA-->
        <header id="header">        
            <a id="logo" href="inicio">
                Blog de videojuegos
            </a>
            <!--MENU-->
            <nav id="nav">
                <ul>
                    <li>
                        <a href="inicio">Inicio</a>
                    </li>
                    <?php
                        if($currentUser): 
                    ?>
                            <li>
                                <a href="mis-entradas">Mis entradas</a>
                            </li>
                    <?php
                        endif;
                        if($currentUser && $currentRol >= 2):
                    ?>
                            <li>
                                <a href="gestionar-categorias">Gestionar categorias</a>
                            </li>
                    <?php 
                        endif;
                        if($currentUser && $currentRol === 3):
                    ?>
                            <li>
                                <a href="gestionar-usuarios">Gestionar usuarios</a>
                            </li>
                    <?php 
                        endif;
                    ?>
                </ul>
                <div id="moreOptions">
                    <button id="openOptions">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul></ul>
                </div>
            </nav>
        </header>
        <div id="container">