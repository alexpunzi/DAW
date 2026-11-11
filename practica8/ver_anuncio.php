<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";

$usuario = requiereUsuario();

$id      = leerId(get('id'));
$anuncio = ($id !== null) ? buscarAnuncio($id) : null;

// Solo se puede ver desde aquí un anuncio propio
if ($anuncio !== null && ($anuncio['usuario'] ?? '') !== $usuario['email']) {
    $anuncio = null;
}

if ($anuncio === null) {
    http_response_code(404);
}

$title          = $anuncio ? "Ver anuncio - " . $anuncio['titulo'] : "Anuncio no encontrado";
$esPartePrivada = true;

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<?php if ($anuncio === null): ?>
    <section>
        <h2>Anuncio no encontrado</h2>
        <p>El anuncio no existe o no es uno de tus anuncios.</p>
        <p><a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">Volver a mis anuncios</a></p>
    </section>
<?php else: ?>
    <?php if (get('foto_exito') === '1'): ?>
        <section class="alerta-exito" role="alert">
            <p>Los datos de la foto son correctos. La subida del fichero se implementará en una práctica posterior.</p>
        </section>
    <?php endif; ?>

    <?php
    $idAnuncio = $id;
    require "includes/layout/ficha_anuncio.php";
    ?>

    <p>
        <a href="<?= e(urlPrivada('anadir_foto.php', ['anuncio' => $anuncio['id']])); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
            Añadir una foto a este anuncio
        </a>
    </p>
    <p><a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">Volver a mis anuncios</a></p>
<?php endif; ?>

<?php
require_once "includes/layout/pie.php";
?>
