<?php
require_once __DIR__ . '/includes/bootstrap.php';

$usuario         = requiereUsuario();
$idUsuarioActual = (int)($_SESSION['id_usuario'] ?? $usuario['id'] ?? 0);

$id      = leerId(get('id'));
$anuncio = ($id !== null) ? buscarAnuncio($id) : null;

// Solo se puede ver desde aquí un anuncio propio (comparamos por ID de usuario en MySQL)
if ($anuncio !== null && (int)($anuncio['id_usuario'] ?? 0) !== $idUsuarioActual) {
    $anuncio = null;
}

if ($anuncio === null) {
    http_response_code(404);
}

if ($anuncio !== null) {
    registrarAnuncioVisitado((int)$anuncio['id']);
}

$title          = $anuncio ? "Ver anuncio - " . $anuncio['titulo'] : "Anuncio no encontrado";
$esPartePrivada = true;

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
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
    require __DIR__ . "/includes/layout/ficha_anuncio.php";
    ?>

    <p>
        <a href="<?= e(urlPrivada('anadir_foto.php', ['anuncio' => $anuncio['id']])); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
            Añadir una foto a este anuncio
        </a>
        &nbsp;|&nbsp;
        <a href="<?= e(urlPrivada('mensaje_anuncio.php', ['id' => $anuncio['id']])); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            Ver mensajes
        </a>
    </p>
    <p><a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">Volver a mis anuncios</a></p>
<?php endif; ?>

<?php
require_once __DIR__ . "/includes/layout/pie.php";
?>