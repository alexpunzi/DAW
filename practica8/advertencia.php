<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";


$title          = "Acceso al anuncio";
$esPartePrivada = false; // Muestra la navegación pública

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<h2>Advertencia</h2>

<p>
    <span class="material-symbols-outlined" aria-hidden="true">warning</span>
    Para poder ver el detalle de un anuncio es necesario estar registrado.
</p>

<?php
require_once "includes/layout/pie.php";
?>