<?php
require_once __DIR__ . '/../lib/funciones.php';

$title        = $title ?? 'PI - Pisos & Inmuebles';
$estilosExtra = $estilosExtra ?? []; 
$scriptsExtra = $scriptsExtra ?? []; 
$bodyClass    = $bodyClass ?? '';    
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($title); ?> | PI - Pisos &amp; Inmuebles</title>

    <!-- Hoja de estilos base -->
    <link rel="stylesheet" href="css/estilos.css" media="screen">

    <?php
    // Inyección de la hoja de estilo preferida del usuario identificado
    if (!empty($_SESSION['estilo'])) {
        $estiloUsuario = $_SESSION['estilo'];

        if ($estiloUsuario === 'noche') {
            echo '<link rel="stylesheet" href="css/noche.css" media="screen">' . "\n";
        } elseif ($estiloUsuario === 'contraste') {
            echo '<link rel="stylesheet" href="css/contraste.css" media="screen">' . "\n";
        } elseif ($estiloUsuario === 'letra-grande') {
            echo '<link rel="stylesheet" href="css/letra-grande.css" media="screen">' . "\n";
        } elseif ($estiloUsuario === 'contraste-letra-grande') {
            // El estilo combinado carga ambos ficheros en orden
            echo '<link rel="stylesheet" href="css/contraste.css" media="screen">' . "\n";
            echo '<link rel="stylesheet" href="css/letra-grande.css" media="screen">' . "\n";
        }
    }
    ?>

    <!-- Hojas de estilo alternativas para la selección desde el navegador -->
    <link rel="alternate stylesheet" href="css/noche.css" title="Modo Noche" media="screen">
    <link rel="alternate stylesheet" href="css/contraste.css" title="Alto contraste" media="screen">
    <link rel="alternate stylesheet" href="css/letra-grande.css" title="Letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/contraste.css" title="Alto contraste y letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/letra-grande.css" title="Alto contraste y letra grande" media="screen">

    <!-- Hoja de estilos para impresión -->
    <link rel="stylesheet" href="css/impresion.css" media="print">

    <!-- Fuentes e iconos -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">

    <!-- Estilos adicionales por página -->
    <?php foreach ($estilosExtra as $css): ?>
        <link rel="stylesheet" href="<?= e($css); ?>" media="screen">
    <?php endforeach; ?>

    <!-- Scripts adicionales por página -->
    <?php foreach ($scriptsExtra as $js): ?>
        <script src="<?= e($js); ?>" defer></script>
    <?php endforeach; ?>
</head>

<body<?= !empty($bodyClass) ? ' class="' . e($bodyClass) . '"' : ''; ?>>