<?php
require_once "includes/lib/funciones.php";


$title          = "Mensaje Enviado";
$esPartePrivada = estaIdentificado();


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";

//lee los parámetros recibidos por GET desde procesadores/respuesta_mensaje.php
$tipo  = get('tipo');
$texto = get('texto');

// Mapeo de identificadores de tipo a su descripción legible
$tiposTexto = [
    'cita'        => 'Solicitar una cita',
    'informacion' => 'Más información',
    'oferta'      => 'Comunicar una oferta',
    'reclamacion' => 'Reclamación'
];

$tipoMostrar = $tiposTexto[$tipo] ?? ($tipo !== '' ? $tipo : 'No especificado');
?>

<section>
    <h2>Mensaje enviado con éxito</h2>

    <p>
        <span class="material-symbols-outlined" aria-hidden="true">check_circle</span>
        Tu mensaje se ha enviado correctamente al anunciante.
    </p>
</section>

<section>
    <h3>Resumen de tu envío</h3>

    <p>Estos son los datos introducidos en el formulario:</p>

    <ul>
        <li>
            <strong>Tipo de mensaje:</strong>
            <?= e($tipoMostrar); ?>
        </li>

        <li>
            <strong>Texto:</strong>
            <?= e($texto); ?>
        </li>
    </ul>
</section>

<?php
require_once "includes/layout/pie.php";
?>