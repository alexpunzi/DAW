<?php
require_once 'includes/bootstrap.php';

// Página pública: cualquier visitante puede ver el detalle (enviar mensaje sí exige identificarse)
// Identificador recibido por la URL (si no es un entero positivo, no hay anuncio)
$id      = leerId(get('id'));
$anuncio = ($id !== null) ? buscarAnuncio($id) : null;

if ($anuncio === null) {
    http_response_code(404);
}
if ($anuncio !== null) {
    registrarAnuncioVisitado($id);
}

$title          = $anuncio ? "Detalle del anuncio - " . $anuncio['titulo'] : "Anuncio no encontrado";
$esPartePrivada = estaIdentificado(); // menú privado solo si hay usuario identificado

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<?php if ($anuncio === null): ?>
    <section>
        <h2>Anuncio no encontrado</h2>
        <p>El anuncio que buscas no existe o el identificador no es válido.</p>
        <p><a href="<?= e(urlInicio()); ?>">Volver a la página de inicio</a></p>
    </section>
<?php else: ?>
    <?php
    $idAnuncio = $id;
    require "includes/layout/ficha_anuncio.php";
    ?>

    <?php if (estaIdentificado()): ?>
        <form action="mensaje.php" method="get">
            <input type="hidden" name="id" value="<?= (int)$id; ?>">
            <button type="submit">
                <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                Enviar mensaje
            </button>
        </form>
    <?php else: ?>
        <p>
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            <a href="index.php">Inicia sesión</a> para enviar un mensaje al anunciante.
        </p>
    <?php endif; ?>
<?php endif; ?>

<?php
require_once "includes/layout/pie.php";
?>