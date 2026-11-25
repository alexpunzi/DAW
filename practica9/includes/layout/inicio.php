<?php

// Estado por defecto: si no se define, se asume que es pública
$esPartePrivada = $esPartePrivada ?? false;
?>

    <!-- Enlace de accesibilidad para saltar directamente al contenido principal del inicio -->
    <a href="<?= e(urlInicio()); ?>#contenido" class="enlace-saltar">Saltar al contenido principal</a>

    <header>
        <div class="header-branding">
            <h1><a href="<?= e(urlInicio()); ?>">PI - Pisos &amp; Inmuebles</a></h1>
        </div>

        <nav aria-label="Navegación principal">
            <?php
            // Carga modular del menú según el estado de autenticación de la vista
            if ($esPartePrivada) {
                require_once __DIR__ . '/nav_privada.php';
            } else {
                require_once __DIR__ . '/nav_publica.php';
            }
            ?>
        </nav>
    </header>

    <main id="contenido" tabindex="-1">
        <?php if ($mensajeFlash = flash_get('exito')): ?>
            <section class="alerta-exito" role="status">
                <p><?= e($mensajeFlash); ?></p>
            </section>
        <?php endif; ?>