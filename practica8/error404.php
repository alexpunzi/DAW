<?php
require_once 'includes/bootstrap.php';

require_once "includes/lib/funciones.php";

$title          = "Error 404 - Página no encontrada";
$esPartePrivada = false; // Muestra la navegación pública 

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Error 404</h2>

    <p>
        <span class="material-symbols-outlined" aria-hidden="true">error</span>
        Lo sentimos, la página que estás buscando no existe o todavía está en construcción.
    </p>

    <p>
        Por favor, vuelve a la <a href="index.php">página de inicio</a>.
    </p>
</section>

<?php
require_once "includes/layout/pie.php";
?>