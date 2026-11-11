<?php

require_once "includes/lib/funciones.php";


$title          = "Enviar Mensaje";
$esPartePrivada = estaIdentificado();


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";

//captura los parametros recibidos a traves del get 
$idAnuncio    = get('id') ?: get('id_anuncio');
$exito        = get('exito');
$erroresRaw   = get('errores');
$listaErrores = !empty($erroresRaw) ? explode('|', $erroresRaw) : [];

$tipoPrevio  = get('tipo');
$textoPrevio = get('texto');

// Detectamos el error específico para cada campo y guardamos su mensaje exacto
$errorTipo   = false;
$errorTexto  = false;
$mensajeTipo = '';
$mensajeTexto = '';

foreach ($listaErrores as $err) {
    $errLower = mb_strtolower($err);
    
    // si el error es  del tipo o motivo
    if (str_contains($errLower, 'tipo') || str_contains($errLower, 'motivo')) {
        $errorTipo = true;
        $mensajeTipo = $err;
    }
    
    // si el error es del texto o mensaje
    if (str_contains($errLower, 'texto') || str_contains($errLower, 'mensaje')) {
        $errorTexto = true;
        $mensajeTexto = $err;
    }
}
?>

<section>
    <h2>Contactar con el anunciante</h2>

    <!-- Notificación de mensaje enviado con éxito -->
    <?php if ($exito === '1'): ?>
        <section class="alerta-exito" role="alert">
            <p>¡Tu mensaje ha sido enviado correctamente al anunciante!</p>
        </section>
    <?php endif; ?>
    
    <form action="<?= e(urlPrivada('includes/procesadores/respuesta_mensaje.php')); ?>" method="post" novalidate>

        <!-- Campo oculto para mantener la referencia del anuncio sobre el que se consulta -->
        <input type="hidden" name="id_anuncio" value="<?= e($idAnuncio); ?>">

        <p>
            <label for="tipo_mensaje">Tipo de mensaje *</label>
            
            
            <div class="campo-con-error">
                <select 
                    id="tipo_mensaje" 
                    name="tipo_mensaje"
                    class="<?= $errorTipo ? 'input-error' : ''; ?>"
                >
                    <option value="">Selecciona el motivo...</option>
                    <option value="cita" <?= $tipoPrevio === 'cita' ? 'selected' : ''; ?>>Solicitar una cita</option>
                    <option value="informacion" <?= $tipoPrevio === 'informacion' ? 'selected' : ''; ?>>Más información</option>
                    <option value="oferta" <?= $tipoPrevio === 'oferta' ? 'selected' : ''; ?>>Comunicar una oferta</option>
                    <option value="reclamacion" <?= $tipoPrevio === 'reclamacion' ? 'selected' : ''; ?>>Reclamación</option>
                </select>

                <?php if ($errorTipo): ?>
                    <span class="error-text">
                        <?= e($mensajeTipo); ?>
                    </span>
                <?php endif; ?>
            </div>
        </p>

        <p>
            <label for="texto_mensaje">Texto del mensaje *</label>
            
            
            <div class="campo-con-error">
                <textarea
                    id="texto_mensaje"
                    name="texto_mensaje"
                    rows="6"
                    cols="50"
                    placeholder="Escribe tu mensaje aquí..."
                    class="<?= $errorTexto ? 'input-error' : ''; ?>"
                ><?= e($textoPrevio); ?></textarea>

                <!-- Mensaje de error individual -->
                <?php if ($errorTexto): ?>
                    <span class="error-text">
                        <?= e($mensajeTexto); ?>
                    </span>
                <?php endif; ?>
            </div>
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