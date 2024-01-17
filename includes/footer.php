        <!--PIE DE PAGINA-->
        </div>
        <footer id="footer">
            <p>Desarrollado por Fredy Palomino &copy; <?= date('Y') ?></p>
        </footer>
    </main>
</body>
<!--FUNCIONALIDAD PERSONALIZADA-->
<script src="public/js/index.js" type="module"></script>
<?php
    switch($view):
        case 'entrada':    
?>
    <script src="public/js/entry.js" type="module"></script>
<?php 
        break;
        case 'mis-entradas':
        case 'inicio':
?>
    <script src="public/js/entries.js" type="module"></script>
<?php 
        case 'formulario-entrada':
?>
    <script src="public/js/formEntry.js" type="module"></script>
<?php
        break;
        case 'gestionar-categorias':
?>
    <script src="public/js/manageCategories.js" type="module"></script>
<?php
        break;
        case 'gestionar-usuarios':
?>
    <script src="public/js/manageUsers.js" type="module"></script>
<?php
        break;
    endswitch; 
?>
</html>