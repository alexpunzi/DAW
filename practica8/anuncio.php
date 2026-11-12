<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";

// Exigir usuario identificado para ver el detalle
requiereUsuario();

// Identificador recibido por la URL (si no es un entero positivo, no hay anuncio)
$id      = leerId(get('id'));
$anuncio = ($id !== null) ? buscarAnuncio($id) : null;

if ($anuncio === null) {
    http_response_code(404);
}

// --- REGISTRAR ANUNCIO VISITADO EN LA COOKIE (1 SEMANA) ---
if ($anuncio !== null) {
    $visitados = [];
    
    // 1. Leer cookie previa si existe
    if (!empty($_COOKIE['ultimos_visitados'])) {
        $raw = json_decode($_COOKIE['ultimos_visitados'], true);
        if (is_array($raw)) {
            foreach ($raw as $val) {
                $vId = leerId($val);
                if ($vId !== null && buscarAnuncio($vId) !== null && !in_array($vId, $visitados, true)) {
                    $visitados[] = $vId;
                }
            }
        }
    }
    
    // 2. Si el ID ya existía en la lista, lo quitamos para ponerlo al principio
    $pos = array_search($id, $visitados, true);
    if ($pos !== false) {
        unset($visitados[$pos]);
    }
    
    // 3. Insertar el ID actual al principio
    array_unshift($visitados, $id);
    
    // 4. Limitar a un máximo de 4 anuncios
    $visitados = array_slice(array_values($visitados), 0, 4);
    
    // 5. Guardar la cookie por 1 semana (7 días)
    setcookie('ultimos_visitados', json_encode($visitados), [
        'expires'  => time() + (7 * 24 * 60 * 60),
        'path'     => '/',
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
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

    <form action="mensaje.php" method="get">
        <input type="hidden" name="id" value="<?= (int)$id; ?>">
        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            Enviar mensaje
        </button>
    </form>
<?php endif; ?>

<?php
require_once "includes/layout/pie.php";
?>