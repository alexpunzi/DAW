<?php
require_once "includes/lib/funciones.php";
require_once "includes/lib/costes.php";

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

//VAlidación 
$errores = [];

foreach (['nombre' => 'El nombre', 'apellidos' => 'Los apellidos', 'calle' => 'La calle',
          'numero' => 'El número', 'puerta' => 'La puerta', 'localidad' => 'La localidad',
          'provincia' => 'La provincia'] as $campo => $nombre) {
    if (campoVacio($d[$campo])) {
        $errores[] = $nombre . ' es obligatorio.';
    }
}

if (campoVacio($d['correo'])) {
    $errores[] = 'El correo electrónico es obligatorio.';
} elseif (!filter_var($d['correo'], FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El correo electrónico no tiene un formato válido.';
}

if ($d['telefono'] !== '' && !preg_match('/^\+?[0-9 ]{9,15}$/', $d['telefono'])) {
    $errores[] = 'El teléfono solo puede contener números, espacios y un + inicial (9 a 15 cifras).';
}

if (!preg_match('/^[0-9]{5}$/', $d['cp'])) {
    $errores[] = 'El código postal debe tener 5 dígitos.';
}

if (!array_key_exists($d['pais'], paises())) {
    $errores[] = 'Debes seleccionar un país válido.';
}

if (!in_array($d['anuncio'], ['1', '2', '3'], true)) {
    $errores[] = 'Debes seleccionar el anuncio a imprimir.';
}

if (!preg_match('/^#[0-9a-fA-F]{6}$/', $d['color_portada'])) {
    $errores[] = 'El color de la portada no es válido.';
}

$copias = filter_var($d['num_copias'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
if ($copias === false) {
    $errores[] = 'El número de copias debe ser un entero entre 1 y 99.';
}

$dpi = filter_var($d['resolucion'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 150, 'max_range' => 900]]);
if ($dpi === false || $dpi % 150 !== 0) {
    $errores[] = 'La resolución debe estar entre 150 y 900 dpi (de 150 en 150).';
}

if (!in_array($d['tipo_impresion'], ['color', 'blanco_negro'], true)) {
    $errores[] = 'Debes indicar si la impresión es a color o en blanco y negro.';
}

if (!in_array($d['imprimir_precio'], ['si', 'no'], true)) {
    $errores[] = 'Debes indicar si se imprime el precio del anuncio.';
}

if ($d['fecha_recepcion'] !== '') {
    $fecha = DateTime::createFromFormat('Y-m-d', $d['fecha_recepcion']);
    if ($fecha === false || $fecha->format('Y-m-d') !== $d['fecha_recepcion']) {
        $errores[] = 'La fecha de recepción no es válida.';
    } elseif ($d['fecha_recepcion'] < date('Y-m-d')) {
        $errores[] = 'La fecha de recepción no puede ser anterior a hoy.';
    }
}

// Con errores: se vuelve al formulario con los datos ya escritos 
if (!empty($errores)) {
    // El texto adicional se recorta para no superar el tamaño máximo de una URL
    $d['texto_adicional'] = mb_substr($d['texto_adicional'], 0, 300);
    redirigir('solicitar_folleto.php', $d + ['errores' => implode('|', $errores)]);
}


//Calculo ficticio del coste 
$paginas = 5;
$fotos   = $paginas * 3;
$esColor = ($d['tipo_impresion'] === 'color');

$unitario = calcularCosteFolleto($paginas, $fotos, $esColor, $dpi);
$total    = calcularCosteTotalPedido($paginas, $fotos, $esColor, $dpi, $copias);

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
