<?php
// Menú de usuario registrado: todos los enlaces conservan el usuario (?usuario=email)
?>
<ul>
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
            Crear anuncio
        </a>
    </li>
    <li>
        <a href="<?= e(urlPrivada('anadir_foto.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
            Añadir foto
        </a>
    </li>
    <li>
        <a href="<?= e(urlPrivada('mis_mensajes.php')); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            Mis mensajes
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
