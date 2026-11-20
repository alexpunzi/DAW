<?php
require_once 'includes/bootstrap.php';
require_once "includes/servicios/folletos.php";
require_once "includes/validaciones/valida_folleto.php";

requiereUsuario();

// Solo se llega aquí enviando el formulario de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('solicitar_folleto.php');
}

// Datos recibidos 
$campos = ['nombre', 'apellidos', 'correo', 'telefono', 'calle', 'numero', 'puerta', 'piso', 'cp',
           'localidad', 'provincia', 'pais', 'anuncio', 'texto_adicional', 'color_portada',
           'num_copias', 'resolucion', 'fecha_recepcion', 'tipo_impresion', 'imprimir_precio'];

$d = [];
foreach ($campos as $campo) {
    $d[$campo] = post($campo);
}

$errores = validarSolicitudFolleto($d);

// Con errores: se vuelve al formulario con los datos ya escritos 
if (!empty($errores)) {
    // El texto adicional se recorta para no superar el tamaño máximo de una URL
    $d['texto_adicional'] = mb_substr($d['texto_adicional'], 0, 300);
    redirigir('solicitar_folleto.php', $d + ['errores' => implode('|', $errores)]);
}


$copias = (int)$d['num_copias'];
$dpi = (int)$d['resolucion'];
$esColor = ($d['tipo_impresion'] === 'color');
$resumenCostes = calcularResumenFolleto($dpi, $esColor, $copias);
$paginas = $resumenCostes['paginas'];
$fotos = $resumenCostes['fotos'];
$unitario = $resumenCostes['unitario'];
$total = $resumenCostes['total'];

$title          = "Solicitud de folleto registrada";
$esPartePrivada = estaIdentificado();

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Solicitud de folleto registrada</h2>

    <section class="alerta-exito" role="alert">
        <p>¡Tu solicitud de folleto publicitario se ha registrado correctamente!</p>
    </section>

    <h3>Datos de la solicitud</h3>
    <dl>
        <dt>Solicitante</dt>
        <dd><?= e($d['nombre'] . ' ' . $d['apellidos']); ?></dd>

        <dt>Contacto</dt>
        <dd><?= e($d['correo'] . ($d['telefono'] !== '' ? ' · ' . $d['telefono'] : '')); ?></dd>

        <dt>Dirección de envío</dt>
        <dd>
            <?= e($d['calle'] . ' ' . $d['numero'] . ', ' . trim($d['piso'] . ' ' . $d['puerta'])); ?>
            · <?= e($d['cp'] . ' ' . $d['localidad']); ?>
            (<?= e($d['provincia']); ?>, <?= e(paises()[$d['pais']]); ?>)
        </dd>

        <dt>Anuncio</dt>
        <dd>Anuncio #<?= e($d['anuncio']); ?></dd>

        <dt>Texto adicional</dt>
        <dd><?= $d['texto_adicional'] !== '' ? e($d['texto_adicional']) : 'Ninguno'; ?></dd>

        <dt>Color de la portada</dt>
        <dd><?= e($d['color_portada']); ?></dd>

        <dt>Resolución</dt>
        <dd><?= (int)$dpi; ?> dpi</dd>

        <dt>Tipo de impresión</dt>
        <dd><?= $esColor ? 'A todo color' : 'Blanco y negro'; ?></dd>

        <dt>Imprimir el precio del anuncio</dt>
        <dd><?= $d['imprimir_precio'] === 'si' ? 'Sí' : 'No'; ?></dd>

        <dt>Fecha aproximada de recepción</dt>
        <dd><?= $d['fecha_recepcion'] !== '' ? e(date('d/m/Y', strtotime($d['fecha_recepcion']))) : 'Sin especificar'; ?></dd>

        <dt>Número de copias</dt>
        <dd><?= (int)$copias; ?></dd>
    </dl>

    <h3>Coste del folleto</h3>
    <p>
        Calculado con <?= $paginas; ?> páginas y <?= $fotos; ?> fotos (valores ficticios hasta que existan
        los anuncios y las fotos en la base de datos).
    </p>
    <ul>
        <li><strong>Precio unitario:</strong> <?= euros($unitario); ?></li>
        <li><strong>Copias:</strong> <?= (int)$copias; ?></li>
        <li><strong>Coste final:</strong> <?= euros($total); ?></li>
    </ul>
</section>

<?php
require_once "includes/layout/pie.php";
?>
