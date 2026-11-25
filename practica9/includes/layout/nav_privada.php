<?php
// La identidad se mantiene en la sesión, nunca en los enlaces.
?>
<ul class="nav-principal">
    <li>
        <a href="<?= e(urlPrivada('index_sesion.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">dashboard</span>
            Inicio
        </a>
    </li>
    <li>
        <a href="<?= e(urlPrivada('buscar.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">search</span>
            Buscar
        </a>
    </li>
    <li>
        <a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">list_alt</span>
            Mis anuncios
        </a>
    </li>
    <li>
        <a href="<?= e(urlPrivada('crear_anuncio.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">add_box</span>
            Publicar
        </a>
    </li>
    <li>
        <a href="<?= e(urlPrivada('mis_mensajes.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            Mensajes
        </a>
    </li>
    <li class="menu-usuario">
        <details>
            <summary>
                <span class="material-symbols-outlined" aria-hidden="true">account_circle</span>
                <?= e($_SESSION['usuario'] ?? 'Mi cuenta'); ?>
                <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
            </summary>
            <ul class="submenu-usuario">
                <li>
                    <a href="<?= e(urlPrivada('mis_datos.php')); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">manage_accounts</span>
                        Mis datos
                    </a>
                </li>
                <li>
                    <a href="<?= e(urlPrivada('configurar.php')); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">palette</span>
                        Configurar
                    </a>
                </li>
                <li>
                    <a href="<?= e(urlPrivada('solicitar_folleto.php')); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">description</span>
                        Solicitar folleto
                    </a>
                </li>
                <li>
                    <a href="includes/procesadores/cerrar_sesion.php">
                        <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                        Cerrar sesión
                    </a>
                </li>
            </ul>
        </details>
    </li>
</ul>
