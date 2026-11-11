<?php

require_once "includes/lib/funciones.php";


$title          = "Página de Búsqueda";
$esPartePrivada = estaIdentificado(); // menú privado solo si hay usuario identificado


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<h2>Formulario de Búsqueda</h2>

<!-- Acción reorientada al controlador de resultados en PHP -->
<form action="resultado_busqueda.php" method="get">
    <?= campoUsuario(); ?>

    <p>
        <label for="tipo_anuncio">Tipo de Anuncio</label>
        <select id="tipo_anuncio" name="tipo_anuncio">
            <option value="">Selecciona un tipo de anuncio...</option>
            <option value="venta" <?= get('tipo_anuncio') === 'venta' ? 'selected' : ''; ?>>Venta</option>
            <option value="alquiler" <?= get('tipo_anuncio') === 'alquiler' ? 'selected' : ''; ?>>Alquiler</option>
        </select>
    </p>

    <p>
        <label for="tipo_vivienda">Tipo de Vivienda</label>
        <select id="tipo_vivienda" name="tipo_vivienda">
            <option value="">Selecciona un tipo de vivienda...</option>
            <option value="garaje" <?= get('tipo_vivienda') === 'garaje' ? 'selected' : ''; ?>>Garaje</option>
            <option value="local" <?= get('tipo_vivienda') === 'local' ? 'selected' : ''; ?>>Local</option>
            <option value="obra_nueva" <?= get('tipo_vivienda') === 'obra_nueva' ? 'selected' : ''; ?>>Obra Nueva</option>
            <option value="oficina" <?= get('tipo_vivienda') === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
            <option value="vivienda" <?= get('tipo_vivienda') === 'vivienda' ? 'selected' : ''; ?>>Vivienda</option>
        </select>
    </p>

    <p>
        <label for="ciudad">Ciudad</label>
        <input type="text" id="ciudad" name="ciudad" value="<?= e(get('ciudad')); ?>">
    </p>

    <p>
        <label for="pais">País</label>
        <select id="pais" name="pais">
            <option value="">Cualquier país...</option>
            <option value="argentina" <?= get('pais') === 'argentina' ? 'selected' : ''; ?>>Argentina</option>
            <option value="espana" <?= get('pais') === 'espana' ? 'selected' : ''; ?>>España</option>
            <option value="mexico" <?= get('pais') === 'mexico' ? 'selected' : ''; ?>>México</option>
        </select>
    </p>

    <p>
        <label for="precio_desde">Precio desde (€)</label>
        <input type="number" id="precio_desde" name="precio_desde" min="0" value="<?= e(get('precio_desde')); ?>">
    </p>

    <p>
        <label for="precio_hasta">Precio hasta (€)</label>
        <input type="number" id="precio_hasta" name="precio_hasta" min="0" value="<?= e(get('precio_hasta')); ?>">
    </p>

    <p>
        <label for="fecha_desde">Fecha de publicación desde</label>
        <input type="date" id="fecha_desde" name="fecha_desde" value="<?= e(get('fecha_desde')); ?>">
    </p>

    <p>
        <label for="fecha_hasta">Fecha de publicación hasta</label>
        <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= e(get('fecha_hasta')); ?>">
    </p>

    <button type="submit">
        <span class="material-symbols-outlined" aria-hidden="true">search</span>
        Buscar Anuncios
    </button>

</form>

<?php
require_once "includes/layout/pie.php";
?>