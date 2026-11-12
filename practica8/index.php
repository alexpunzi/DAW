<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";

if (estaIdentificado()) {
    redirigir('index_sesion.php');
}

$title          = "Página principal";
$esPartePrivada = false; // Carga el menú de navegación pública (nav_publica.php)

$scriptsExtra = scriptsValidacion([
    'js/utilidades.js',
    'js/validadores.js',
    'js/login.js'
]);


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";

// Captura los errores desde la sesión usando Flashdata
$flashLogin = flash_get_many(['error', 'email_previo']);
$codigoError = $flashLogin['error'];
$emailPrevio = $flashLogin['email_previo'] ?? '';

$mensajesError = [
    'vacio' => 'Por favor, rellena el correo electrónico y la contraseña.',
    'credenciales' => 'El correo electrónico o la contraseña son incorrectos.',
    'acceso' => 'Debes iniciar sesión para acceder a esa página.'
]
?>

<!-- Notificación gráfica de errores de inicio de sesión -->
<?php if ($codigoError): ?>
    <section class="alerta-error" role="alert">
        <p>
            <?php 
            // Comprobamos si el código ('vacio', 'credenciales'...) está en nuestro diccionario
            if (isset($mensajesError[$codigoError])) {
                // Si existe, imprimimos la frase larga y amigable
                echo e($mensajesError[$codigoError]);
            } else {
                // Si no existe, imprimimos el error tal y como nos llegó
                echo e($codigoError);
            }
            ?>
        </p>
    </section>
<?php endif; ?>

<!-- Formulario modular de inicio de sesión (includes/layout/acceso.php) -->
<?php require_once "includes/layout/acceso.php"; ?>

<section id="busqueda-rapida">
    <h2>Búsqueda Rápida</h2>

    <form action="resultado_busqueda.php" method="get">
        <p>
            <label for="texto_busqueda">
                <span class="material-symbols-outlined" aria-hidden="true">key</span>
                Palabra clave
            </label>
            <input type="text" id="texto_busqueda" name="texto_busqueda" placeholder="Ej. Chalet, Alicante...">
        </p>

        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">search</span>
            Buscar
        </button>
    </form>
</section>

<section>
    <h2>Últimos anuncios publicados</h2>

    <ul class="cards-grid">
        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 1])); ?>">Chalet Independiente con Piscina</a>
            </h3>

            <img src="img/chalet-piscina.jpg"
                alt="Chalet independiente con piscina en Altea"
                width="150"
                height="100">

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                Altea, Alicante, España
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(320000); ?> |
                <b><span class="material-symbols-outlined" aria-hidden="true">bed</span> Habitaciones</b> 4 |
                <b><span class="material-symbols-outlined" aria-hidden="true">bathtub</span> Baños</b> 3
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
                15/10/2026
            </p>

            <p>
                Espectacular chalet independiente con piscina privada y amplio jardín en zona residencial tranquila.
            </p>
        </li>

        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 3])); ?>">Estudio Moderno Cerca de la Universidad</a>
            </h3>

            <img src="img/estudio.jpg"
                alt="Estudio moderno cerca de la universidad en San Vicente del Raspeig"
                width="150"
                height="100">

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                San Vicente del Raspeig, Alicante, España
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(95000); ?> |
                <b><span class="material-symbols-outlined" aria-hidden="true">bed</span> Habitaciones</b> 1 |
                <b><span class="material-symbols-outlined" aria-hidden="true">bathtub</span> Baño</b> 1
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
                25/10/2026
            </p>

            <p>
                Estudio ideal para inversores o estudiantes, muy cerca del campus universitario.
            </p>
        </li>

        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 2])); ?>">Ático Luminoso en el Centro Histórico</a>
            </h3>

            <img src="img/piso-terraza.jpg"
                alt="Ático luminoso con terraza en Alicante"
                width="150"
                height="100">

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                Alicante, Alicante, España
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(185000); ?> |
                <b><span class="material-symbols-outlined" aria-hidden="true">bed</span> Habitaciones</b> 2 |
                <b><span class="material-symbols-outlined" aria-hidden="true">bathtub</span> Baños</b> 2
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
                20/10/2026
            </p>

            <p>
                Ático reformado con terraza exterior y luz natural todo el día.
            </p>
        </li>

        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 4])); ?>">Piso Amplio en Ruzafa</a>
            </h3>

            <img src="img/piso-valencia.jpg"
                alt="Piso amplio en el barrio de Ruzafa, Valencia"
                width="150"
                height="100">

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                Valencia, Valencia, España
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(240000); ?> |
                <b><span class="material-symbols-outlined" aria-hidden="true">bed</span> Habitaciones</b> 3 |
                <b><span class="material-symbols-outlined" aria-hidden="true">bathtub</span> Baños</b> 2
            </p>

            <p>
                <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
                01/11/2026
            </p>

            <p>
                Vivienda señorial en el moderno barrio de Ruzafa con techos altos y amplio balcón.
            </p>
        </li>

    </ul>
</section>

<?php
require_once "includes/layout/pie.php";
?>