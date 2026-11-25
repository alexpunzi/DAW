<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/repositorios/carga_estilos.php';

$title          = "Configurar apariencia";
$esPartePrivada = true;

$usuario = requiereUsuario();
$estilos = obtenerEstilos();

$estiloActualId = (int)($_SESSION['id_estilo'] ?? ($usuario['id_estilo'] ?? 1));
$mensajeExito   = flash_get('exito');
$mensajeError   = flash_get('error');

// Asignamos un icono de Material Symbols según el nombre o ID del estilo
function iconoParaEstilo(string $nombre): string
{
    $n = mb_strtolower($nombre);
    if (str_contains($n, 'noche')) {
        return 'dark_mode';
    }
    if (str_contains($n, 'contraste') && str_contains($n, 'letra')) {
        return 'accessibility_new';
    }
    if (str_contains($n, 'contraste')) {
        return 'contrast';
    }
    if (str_contains($n, 'letra')) {
        return 'format_size';
    }
    return 'light_mode';
}

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
?>

<section class="configurar-seccion">
    <h2>
        Configurar apariencia
    </h2>
    <p>Elige el tema visual con el que prefieres navegar por <strong>PI - Pisos &amp; Inmuebles</strong>. El cambio se aplica al instante. Solo se guarda en tu perfil para próximas visitas si has iniciado sesión con la opción «Recordarme»; si no, dura lo que dure esta sesión.</p>

    <?php if ($mensajeExito): ?>
        <section class="alerta-exito" role="alert">
            <p><?= e($mensajeExito); ?></p>
        </section>
    <?php endif; ?>

    <?php if ($mensajeError): ?>
        <section class="alerta-error" role="alert">
            <p><?= e($mensajeError); ?></p>
        </section>
    <?php endif; ?>

    <form action="includes/procesadores/respuesta_configurar.php" method="post" class="form-estilos">
        <ul class="cards-grid opciones-estilos">
            <?php foreach ($estilos as $estilo): ?>
                <?php
                $idEst    = (int)$estilo['id'];
                $esActivo = ($estiloActualId === $idEst);
                $icono    = iconoParaEstilo($estilo['nombre']);
                ?>
                <li class="card tarjeta-estilo<?= $esActivo ? ' estilo-activo' : ''; ?>">
                    <label for="estilo_<?= $idEst; ?>" class="label-tarjeta-estilo">
                        <div class="cabecera-tarjeta-estilo">
                            <span class="material-symbols-outlined icono-estilo" aria-hidden="true">
                                <?= e($icono); ?>
                            </span>

                            <?php if ($esActivo): ?>
                                <span class="badge-activo">Activo</span>
                            <?php endif; ?>
                        </div>

                        <h3><?= e($estilo['nombre']); ?></h3>

                        <?php if (!empty($estilo['descripcion'])): ?>
                            <p><?= e($estilo['descripcion']); ?></p>
                        <?php endif; ?>

                        <div class="selector-radio">
                            <input type="radio"
                                   id="estilo_<?= $idEst; ?>"
                                   name="estilo"
                                   value="<?= $idEst; ?>"
                                   <?= $esActivo ? 'checked' : ''; ?>>
                            <span><?= $esActivo ? 'Tema seleccionado' : 'Seleccionar tema'; ?></span>
                        </div>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="acciones-form">
            <button type="submit">
                <span class="material-symbols-outlined" aria-hidden="true">check_circle</span>
                Aplicar estilo seleccionado
            </button>
        </p>
    </form>
</section>

<?php require_once __DIR__ . "/includes/layout/pie.php"; ?>