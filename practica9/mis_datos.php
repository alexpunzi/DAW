<?php
require_once 'includes/bootstrap.php';
require_once 'includes/repositorios/carga_usuarios.php';
require_once 'includes/repositorios/carga_paises.php';

// Control de acceso: solo usuarios identificados
if (empty($_SESSION['usuario'])) {
    flash_set('error', 'Debes iniciar sesión para ver tus datos.');
    redirigir('index.php');
}

$usuarioActual = buscarUsuario($_SESSION['usuario']);
if ($usuarioActual === null) {
    flash_set('error', 'No se encontraron tus datos en la base de datos.');
    redirigir('index.php');
}

$misDatosFlash = flash_get_many(['mis_datos_errores', 'mis_datos_form', 'mis_datos_exito']);
$listaErrores  = is_array($misDatosFlash['mis_datos_errores']) ? $misDatosFlash['mis_datos_errores'] : [];
$exito         = $misDatosFlash['mis_datos_exito'] === true;

// Si venimos de un envío con errores mostramos lo enviado; si no, los datos de la BD
$datosPrevios = is_array($misDatosFlash['mis_datos_form'])
    ? $misDatosFlash['mis_datos_form']
    : [
        'nombre'           => $usuarioActual['nombre'],
        'email'            => $usuarioActual['email'],
        'sexo'             => $usuarioActual['sexo'],
        'fecha_nacimiento' => $usuarioActual['fnacimiento'],
        'ciudad'           => $usuarioActual['ciudad'],
        'pais'             => $usuarioActual['pais'],
        'foto'             => $usuarioActual['foto'],
    ];

$paises     = obtenerPaises();
$modo       = 'edicion';
$actionForm = 'includes/procesadores/respuesta_mis_datos.php';

$title          = "Mis datos";
$esPartePrivada = true;
$scriptsExtra   = scriptsValidacion([
    'js/utilidades.js',
    'js/validadores.js',
    'js/registro.js'
]);

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<h2>Mis datos</h2>
<p><small>* Campos obligatorios</small></p>

<?php if ($exito): ?>
    <section class="alerta-exito" role="alert">
        <p>Los datos se han validado correctamente (en la próxima práctica se guardarán en la base de datos).</p>
    </section>
<?php endif; ?>

<?php require 'includes/vistas/form_usuario.php'; ?>

<?php require_once "includes/layout/pie.php"; ?>