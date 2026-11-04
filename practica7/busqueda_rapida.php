<?php
require_once "includes/lib/funciones.php";

$title          = "Resultados de búsqueda";
$esPartePrivada = false; // Muestra la navegación pública 

$textoBusqueda = get('texto_busqueda');

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Resultados de la búsqueda</h2>

    <?php if (!empty($textoBusqueda)): ?>
        <p>Mostrando resultados para la palabra clave: <strong><?= e($textoBusqueda); ?></strong></p>
    <?php endif; ?>

    <p>Se han encontrado 2 anuncios que coinciden con tus criterios:</p>

    
    <ul class="cards-grid">
        <li class="card">
            <h3><a href="detalle_anuncio.php?id=2">Piso Exclusivo de 4 Habitaciones y 3 Baños</a></h3>

            <img src="img/piso-valencia.jpg"
                alt="Piso en venta en Passatge de Ripalda, Valencia"
                width="600"
                height="400">

            <p><b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b> Sant Francesc, Valencia, España</p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(1400000); ?></p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b> 12/04/2026</p>
        </li>

        <li class="card">
            <h3><a href="advertencia.php?id=1">Chalet Independiente con Piscina</a></h3>

            <img src="img/chalet-piscina.jpg"
                alt="Chalet independiente con piscina en Altea"
                width="600"
                height="400">

            <p><b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b> Altea, Alicante, España</p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(320000); ?></p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b> 22/09/2026</p>
        </li>
    </ul>
</section>

<?php
require_once "includes/layout/pie.php";
?>