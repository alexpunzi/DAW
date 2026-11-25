<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/repositorios/carga_paises.php';

$registroFlash = flash_get_many(['registro_errores', 'registro_datos', 'registro_exito', 'registro_error_general']);
$listaErrores = is_array($registroFlash['registro_errores'])
    ? $registroFlash['registro_errores']
    : [];
$datosPrevios = is_array($registroFlash['registro_datos'])
    ? $registroFlash['registro_datos']
    : [];
$exito        = $registroFlash['registro_exito'] === true;
$errorGeneral = is_string($registroFlash['registro_error_general'])
    ? $registroFlash['registro_error_general']
    : '';

$paises = obtenerPaises();

$mapaPaises = [];
foreach ($paises as $p) {
    $mapaPaises[(int)$p['id']] = $p['nombre'];
}
$mapaSexos = [
    '1' => 'Hombre',
    '2' => 'Mujer',
];

$title          = "Registro";
$esPartePrivada = false;

$scriptsExtra = scriptsValidacion([
    'js/utilidades.js',
    'js/validadores.js',
    'js/registro.js'
]);

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
?>

<h2>Registro de Usuario</h2>

<p><small>* Campos obligatorios</small></p>

<?php if ($errorGeneral !== ''): ?>
    <section class="alerta-error" role="alert">
        <p><?= e($errorGeneral); ?></p>
    </section>
<?php endif; ?>

<?php if ($exito): ?>
    <section class="alerta-exito" role="alert">
        <p>¡Registro completado con éxito! Bienvenido/a, <strong><?= e($datosPrevios['nombre'] ?? ''); ?></strong>.</p>
    </section>

    <section>
        <h3>Datos introducidos</h3>
        <ul>
            <li><strong>Nombre:</strong> <?= e($datosPrevios['nombre'] ?? ''); ?></li>
            <li><strong>Apellido/s:</strong> <?= e($datosPrevios['apellidos'] ?? ''); ?></li>
            <li><strong>Email:</strong> <?= e($datosPrevios['email'] ?? ''); ?></li>
            <li><strong>Sexo:</strong> <?= e($mapaSexos[(string)($datosPrevios['sexo'] ?? '')] ?? 'No especificado'); ?></li>
            <li><strong>Fecha de nacimiento:</strong> <?= e($datosPrevios['fecha_nacimiento'] ?? ''); ?></li>
            <li><strong>Ciudad:</strong> <?= e($datosPrevios['ciudad'] ?? ''); ?></li>
            <li><strong>País:</strong> <?= e($mapaPaises[(int)($datosPrevios['pais'] ?? 0)] ?? ''); ?></li>
        </ul>
        <p><a href="index.php">Ir a la página principal para iniciar sesión</a></p>
    </section>
<?php else: ?>
    <?php
    $modo       = 'registro';
    $actionForm = 'includes/procesadores/respuesta_registro.php';
    require __DIR__ . "/includes/vistas/form_usuario.php";
    ?>
<?php endif; ?>

<?php
require_once __DIR__ . "/includes/layout/pie.php";
?>