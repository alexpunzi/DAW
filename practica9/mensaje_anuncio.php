<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/repositorios/carga_mensajes.php';

$usuario         = requiereUsuario();
$idUsuarioActual = (int)($_SESSION['id_usuario'] ?? $usuario['id'] ?? 0);

// Aceptamos tanto ?id=... como ?anuncio=... por compatibilidad con tus enlaces
$idAnuncio = leerId(get('id')) ?? leerId(get('anuncio'));
$anuncio   = ($idAnuncio !== null) ? buscarAnuncio($idAnuncio) : null;

// Solo el propietario del anuncio puede ver sus mensajes recibidos
if ($anuncio !== null && (int)($anuncio['id_usuario'] ?? 0) !== $idUsuarioActual) {
    $anuncio = null;
}

if ($anuncio === null) {
    http_response_code(404);
}

$mensajes      = ($anuncio !== null) ? obtenerMensajesDeAnuncio((int)$anuncio['id']) : [];
$totalMensajes = count($mensajes);

$title          = $anuncio ? "Mensajes - " . $anuncio['titulo'] : "Anuncio no encontrado";
$esPartePrivada = true;

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
?>

<?php if ($anuncio === null): ?>
    <section>
        <h2>Anuncio no disponible</h2>
        <p>El anuncio seleccionado no existe o no pertenece a tu cuenta.</p>
        <p><a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">Volver a Mis anuncios</a></p>
    </section>
<?php else: ?>
    <section class="conBorde">
        <h2>Mensajes recibidos en el anuncio #<?= (int)$anuncio['id']; ?></h2>
        <h3><?= e($anuncio['titulo']); ?></h3>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">sell</span>
            <strong>Tipo:</strong> <?= e($anuncio['tipo_anuncio']); ?> · <?= e($anuncio['tipo_vivienda']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
            <strong>Localización:</strong> <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['pais']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">euro</span>
            <strong>Precio:</strong> <?= euros($anuncio['precio']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            <strong>Total de mensajes recibidos:</strong> <?= (int)$totalMensajes; ?>
        </p>

        <p>
            <a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">
                &larr; Volver a Mis anuncios
            </a>
            &nbsp;|&nbsp;
            <a href="<?= e(urlPrivada('ver_anuncio.php', ['id' => $anuncio['id']])); ?>">
                Ver ficha completa del anuncio
            </a>
        </p>
    </section>

    <section>
        <h3>Listado de mensajes (<?= (int)$totalMensajes; ?>)</h3>

        <?php if ($totalMensajes === 0): ?>
            <p>Este anuncio todavía no ha recibido ningún mensaje.</p>
        <?php else: ?>
            <ul class="cards-grid">
                <?php foreach ($mensajes as $msg): ?>
                    <?php
                    $fechaMsg = !empty($msg['fecha'])
                        ? date('d/m/Y H:i', strtotime($msg['fecha']))
                        : 'Fecha no disponible';
                    ?>
                    <li class="card">
                        <p>
                            <span class="material-symbols-outlined" aria-hidden="true">label</span>
                            <strong>Tipo:</strong> <?= e($msg['tipo_mensaje']); ?>
                        </p>

                        <p>
                            <span class="material-symbols-outlined" aria-hidden="true">person</span>
                            <strong>De:</strong>
                            <a href="<?= e(urlPrivada('perfil_usuario.php', ['id' => $msg['id_remitente']])); ?>">
                                <?= e($msg['remitente']); ?>
                            </a>
                            (<?= e($msg['email_remitente']); ?>)
                        </p>

                        <p>
                            <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                            <strong>Fecha:</strong> <?= e($fechaMsg); ?>
                        </p>

                        <p>
                            <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                            <strong>Mensaje:</strong><br>
                            <?= nl2br(e($msg['texto'])); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . "/includes/layout/pie.php"; ?>