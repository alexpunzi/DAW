<?php
require_once 'includes/bootstrap.php';

$title          = "Panel de Usuario - Inicio";
$esPartePrivada = true; // Activa la navegación privada (nav_privada.php)

// Solo accesible con un usuario identificado (si no, vuelve al formulario de acceso)
$usuario = requiereUsuario();

// Cargamos los 5 últimos anuncios desde la base de datos
$ultimosAnuncios = obtenerUltimosAnuncios(5);

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<p class="mensaje-bienvenida">
    <b><?= e(saludoPorHora()); ?>, <?= e($usuario['nombre']); ?>.</b>
    <?php if (cookiesAceptadas() && !empty($_SESSION['ultima_sesion_previa'])): ?>
        <br><small>Tu última sesión fue el <?= e(formatearFechaHora((int)$_SESSION['ultima_sesion_previa'])); ?>.</small>
    <?php endif; ?>
</p>

<section id="busqueda-rapida">
    <h2>Búsqueda Rápida</h2>

    <form action="resultado_busqueda.php" method="get">
        <p>
            <label for="texto_busqueda">
                <span class="material-symbols-outlined" aria-hidden="true">key</span>
                Palabra clave
            </label>
            <input type="text" id="texto_busqueda" name="texto_busqueda" placeholder="Ej. Chalet, Alicante...">
        </p>

        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">search</span>
            Buscar
        </button>
    </form>
</section>

<section>
    <h2>Últimos anuncios publicados</h2>

    <?php if (empty($ultimosAnuncios)): ?>
        <p>Todavía no hay anuncios publicados en la base de datos.</p>
    <?php else: ?>
        <ul class="cards-grid">
            <?php foreach ($ultimosAnuncios as $anuncio): ?>
                <?php
                $fechaOk = !empty($anuncio['fecha']) ? date('d/m/Y', strtotime($anuncio['fecha'])) : '';
                $altFoto = !empty($anuncio['alternativo']) ? $anuncio['alternativo'] : $anuncio['titulo'];
                ?>
                <li class="card">
                    <h3>
                        <a href="<?= e(urlPrivada('anuncio.php', ['id' => $anuncio['id']])); ?>">
                            <?= e($anuncio['titulo']); ?>
                        </a>
                    </h3>

                    <img src="<?= e($anuncio['foto_principal']); ?>"
                         alt="<?= e($altFoto); ?>"
                         width="150"
                         height="100">

                    <p>
                        <b>
                            <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                            Localización
                        </b>
                        <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['pais']); ?>
                    </p>

                    <p>
                        <b>
                            <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                            Precio
                        </b>
                        <?= euros($anuncio['precio']); ?> |
                        <b>
                            <span class="material-symbols-outlined" aria-hidden="true">bed</span>
                            Habitaciones
                        </b>
                        <?= (int)$anuncio['habitaciones']; ?> |
                        <b>
                            <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
                            <?= (int)$anuncio['banos'] === 1 ? 'Baño' : 'Baños'; ?>
                        </b>
                        <?= (int)$anuncio['banos']; ?>
                    </p>

                    <p>
                        <b>
                            <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                            Publicado el
                        </b>
                        <?= e($fechaOk); ?>
                    </p>

                    <p>
                        <?= e($anuncio['descripcion']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php
require_once "includes/layout/ultimos_visitados.php";
require_once "includes/layout/pie.php";
?>