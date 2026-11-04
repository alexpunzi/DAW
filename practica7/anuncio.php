<?php
require_once "includes/lib/funciones.php";
require_once "datos/anuncios.php";


$id = (int) get('id');
if ($id <= 0) {
    $id = 1; // ID por defecto si no se recibe uno válido
}


$claveParidad = ($id % 2 === 0) ? 'par' : 'impar';
$anuncio      = $anunciosFicticios[$claveParidad];


$title          = "Detalle del anuncio - " . $anuncio['titulo'];
$esPartePrivada = true; // Muestra el menú de usuario registrado si corresponde


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<article>
    <h2><?= e($anuncio['titulo']); ?> <small>(Anuncio #<?= $id; ?>)</small></h2>

    <section class="fotos">
        <img src="<?= e($anuncio['foto_principal']); ?>"
            alt="Foto principal de <?= e($anuncio['titulo']); ?>"
            width="600"
            height="400">

        <h3>Más fotos</h3>

        <?php if (!empty($anuncio['fotos_miniaturas'])): ?>
            <?php foreach ($anuncio['fotos_miniaturas'] as $foto): ?>
                <img class="miniatura" src="<?= e($foto); ?>"
                    alt="Miniatura de <?= e($anuncio['titulo']); ?>"
                    width="120"
                    height="80">
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="conBorde">

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">sell</span>
            Tipo de anuncio: <?= e($anuncio['tipo_anuncio']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">home</span>
            Tipo de vivienda: <?= e($anuncio['tipo_vivienda']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
            Localización: <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['provincia']); ?>, <?= e($anuncio['pais']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">euro</span>
            Precio: <?= euros($anuncio['precio']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">bed</span>
            Habitaciones: <?= (int) $anuncio['caracteristicas']['habitaciones']; ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
            Baños: <?= (int) $anuncio['caracteristicas']['banos']; ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
            Fecha de publicación: <?= e($anuncio['fecha']); ?>
        </p>

        <p>
            <?= e($anuncio['descripcion']); ?>
        </p>

        <h3>Características</h3>

        <ul>
            <li>
                <span class="material-symbols-outlined" aria-hidden="true">stairs</span>
                Planta: <?= e($anuncio['caracteristicas']['planta']); ?>
            </li>

            <li>
                <span class="material-symbols-outlined" aria-hidden="true">square_foot</span>
                Superficie: <?= (int) $anuncio['caracteristicas']['superficie']; ?> m²
            </li>

            <li>
                <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                Año de construcción: <?= (int) $anuncio['caracteristicas']['ano_construccion']; ?>
            </li>
        </ul>

    </section>

    <form action="mensaje.php" method="get">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            Enviar mensaje
        </button>
    </form>

</article>

<?php
require_once "includes/layout/pie.php";
?>