<?php
require_once 'includes/bootstrap.php';

if (estaIdentificado()) {
    redirigir('index_sesion.php');
}

$ultimosAnuncios = obtenerUltimosAnuncios(5);

$title          = "Página principal";
$esPartePrivada = false; //carga el menú de navegación pública (nav_publica.php)

$scriptsExtra = scriptsValidacion([
    'js/utilidades.js',
    'js/validadores.js',
    'js/login.js'
]);

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";

//captura los errores desde la sesión usando Flashdata
$flashLogin  = flash_get_many(['error', 'email_previo']);
$codigoError = $flashLogin['error'];
$emailPrevio = $flashLogin['email_previo'] ?? '';

//si en este equipo hay una cookie "recordarme" vigente (aunque se cerrase la sesión),
//se rellena el correo y se deja marcada la casilla
$emailRecordado   = emailRecordado();
$recordadoPrevio  = ($emailRecordado !== null);
if ($emailPrevio === '' && $emailRecordado !== null) {
    $emailPrevio = $emailRecordado;
}

$mensajesError = [
    'vacio'        => 'Por favor, rellena el correo electrónico y la contraseña.',
    'credenciales' => 'El correo electrónico o la contraseña son incorrectos.',
    'acceso'       => 'Debes iniciar sesión para acceder a esa página.'
];
?>

<!-- Notificación gráfica de errores de inicio de sesión -->
<?php if ($codigoError): ?>
    <section class="alerta-error" role="alert">
        <p>
            <?php
            if (isset($mensajesError[$codigoError])) {
                echo e($mensajesError[$codigoError]);
            } else {
                echo e($codigoError);
            }
            ?>
        </p>
    </section>
<?php endif; ?>

<!-- Formulario modular de inicio de sesión (includes/layout/acceso.php) -->
<?php require_once "includes/layout/acceso.php"; ?>

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
                        <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                        <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['pais']); ?>
                    </p>

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros($anuncio['precio']); ?> |
                        <b><span class="material-symbols-outlined" aria-hidden="true">bed</span> Habitaciones</b> <?= (int)$anuncio['habitaciones']; ?> |
                        <b><span class="material-symbols-outlined" aria-hidden="true">bathtub</span> <?= (int)$anuncio['banos'] === 1 ? 'Baño' : 'Baños'; ?></b> <?= (int)$anuncio['banos']; ?>
                    </p>

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
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
require_once "includes/layout/pie.php";
?>