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


    <link rel="stylesheet" href="css/estilos.css" media="screen">

    <link rel="alternate stylesheet" href="css/noche.css" title="Modo Noche" media="screen">
    <link rel="alternate stylesheet" href="css/contraste.css" title="Alto contraste" media="screen">
    <link rel="alternate stylesheet" href="css/letra-grande.css" title="Letra grande" media="screen">


    <link rel="stylesheet" href="css/impresion.css" media="print">

    <link rel="alternate stylesheet" href="css/contraste.css" title="Alto contraste y letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/letra-grande.css" title="Alto contraste y letra grande" media="screen">


    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">


    <?php foreach ($estilosExtra as $css): ?>
        <link rel="stylesheet" href="<?= e($css); ?>" media="screen">
    <?php endforeach; ?>

    <?php foreach ($scriptsExtra as $js): ?>
        <script src="<?= e($js); ?>" defer></script>
    <?php endforeach; ?>
</head>

<body<?= !empty($bodyClass) ? ' class="' . e($bodyClass) . '"' : ''; ?>>