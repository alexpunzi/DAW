<?php
require_once 'includes/bootstrap.php';
//funciones auxiliares 
require_once "includes/lib/funciones.php";
$title          = "Panel de Usuario - Inicio";
$esPartePrivada = true; // Activa la navegación privada (nav_privada.php)

// Solo accesible con un usuario identificado (si no, vuelve al formulario de acceso)
$usuario = requiereUsuario();
require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<p class="mensaje-bienvenida">
    <b><?= e(saludoPorHora()); ?>, <?= e($usuario['nombre']); ?>.</b>
</p>

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
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                Altea, Alicante, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(320000); ?> |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bed</span>
                    Habitaciones
                </b>
                4 |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
                    Baños
                </b>
                3
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                    Publicado el
                </b>
                22/09/2026
            </p>

            <p>
                Chalet amplio con jardín y piscina, ideal para familias
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
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                San Vicente del Raspeig, Alicante, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(95000); ?> |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bed</span>
                    Habitaciones
                </b>
                1 |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
                    Baño
                </b>
                1
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                    Publicado el
                </b>
                25/10/2026
            </p>

            <p>
                Coqueto estudio ideal para inversores o estudiantes, muy cerca del campus universitario.
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
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                Alicante, Alicante, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(185000); ?> |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bed</span>
                    Habitaciones
                </b>
                2 |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
                    Baños
                </b>
                2
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                    Publicado el
                </b>
                20/10/2026
            </p>

            <p>
                Ático céntrico reformado con terraza exterior y luz natural todo el día.
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
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                Valencia, Valencia, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(240000); ?> |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bed</span>
                    Habitaciones
                </b>
                3 |
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
                    Baños
                </b>
                2
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                    Publicado el
                </b>
                01/11/2026
            </p>

            <p>
                Vivienda señorial en el moderno barrio de Ruzafa con techos altos, molduras originales y amplio balcón.
            </p>
        </li>

    </ul>
</section>

<?php
// 5. Cargar el cierre modular (main, footer, scripts y cierres HTML)
require_once "includes/layout/pie.php";
?>