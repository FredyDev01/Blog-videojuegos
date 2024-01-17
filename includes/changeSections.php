<ul id="changeSections">
    <li>
        <a href="#main" id="change-main" class="btn btnSm roundedLg btnLigth">Contenido</a>
    </li>
    <?php
        if($currentUser):
    ?>
            <li>
                <a href="#actions" id="change-actions" class="btn btnSm roundedLg btnLigth">Acciones</a>
            </li>
    <?php
        else:
    ?>
            <li>
                <a href="#login" id="change-login" class="btn btnSm roundedLg btnLigth">Identificate</a>
            </li>
            <li>
                <a href="#register" id="change-register" class="btn btnSm roundedLg btnLigth">Registrate</a>
            </li>
    <?php
        endif;    
    ?>
</ul>