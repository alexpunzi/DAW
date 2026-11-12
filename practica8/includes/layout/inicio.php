<?php
require_once __DIR__ . '/../lib/funciones.php';

//estado por defecto, si no se define se asume que es publivas 
$esPartePrivada = $esPartePrivada ?? false;
?>

    <!-- Enlace de accesibilidad para saltar directamente al contenido principal -->
    <a href="#contenido" class="enlace-saltar">Saltar al contenido principal</a>

    <header>
        <div class="header-branding">
            <h1><a href="<?= e(urlInicio()); ?>">PI - Pisos &amp; Inmuebles</a></h1>
            <p class="slogan">El portal de tu vivienda</p>
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

    <main id="contenido">
        <?php if ($mensajeFlash = flash_get('exito')): ?>
            <section class="alerta-exito" role="status">
                <p><?= e($mensajeFlash); ?></p>
            </section>
        <?php endif; ?>