<?php

require_once "includes/lib/funciones.php";


$title          = "Enviar Mensaje";
$esPartePrivada = true; 


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";

//captura los parametros recibidos a traves del get 
$idAnuncio    = get('id') ?: get('id_anuncio');
$exito        = get('exito');
$erroresRaw   = get('errores');
$listaErrores = !empty($erroresRaw) ? explode('|', $erroresRaw) : [];

$tipoPrevio  = get('tipo');
$textoPrevio = get('texto');
?>

<section>
    <h2>Contactar con el anunciante</h2>

    <!-- Notificación de mensaje enviado con éxito -->
    <?php if ($exito === '1'): ?>
        <section class="alerta-exito" role="alert">
            <p>¡Tu mensaje ha sido enviado correctamente al anunciante!</p>
        </section>
    <?php endif; ?>

    <!-- Notificación de errores devueltos por el procesador backend -->
    <?php if (!empty($listaErrores)): ?>
        <section class="alerta-error" role="alert">
            <p><strong>Por favor, corrige los siguientes errores:</strong></p>
            <ul>
                <?php foreach ($listaErrores as $error): ?>
                    <li><?= e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    
    <form action="procesadores/respuesta_mensaje.php" method="post" novalidate>

        <!-- Campo oculto para mantener la referencia del anuncio sobre el que se consulta -->
        <input type="hidden" name="id_anuncio" value="<?= e($idAnuncio); ?>">

        <p>
            <label for="tipo_mensaje">Tipo de mensaje *</label>
            <select id="tipo_mensaje" name="tipo_mensaje">
                <option value="">Selecciona el motivo...</option>
                <option value="cita" <?= $tipoPrevio === 'cita' ? 'selected' : ''; ?>>Solicitar una cita</option>
                <option value="informacion" <?= $tipoPrevio === 'informacion' ? 'selected' : ''; ?>>Más información</option>
                <option value="oferta" <?= $tipoPrevio === 'oferta' ? 'selected' : ''; ?>>Comunicar una oferta</option>
            </select>
        </p>

        <p>
            <label for="texto_mensaje">Texto del mensaje *</label>
            <textarea
                id="texto_mensaje"
                name="texto_mensaje"
                rows="6"
                cols="50"
                placeholder="Escribe tu mensaje aquí..."
            ><?= e($textoPrevio); ?></textarea>
        </p>

        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">send</span>
            Enviar mensaje
        </button>

    </form>
</section>

<?php
require_once "includes/layout/pie.php";
?>