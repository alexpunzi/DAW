<?php
//funciones auxiliares 
require_once "includes/lib/funciones.php";
$title          = "Panel de Usuario - Inicio";
$esPartePrivada = true; // Activa la navegación privada (nav_privada.php)

// Solo accesible con un usuario identificado (si no, vuelve al formulario de acceso)
$usuario = requiereUsuario();
$nombreMostrar = $usuario['nombre'];


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<p class="mensaje-bienvenida">
    <b>¡Bienvenido/a, <?= e($nombreMostrar); ?>!</b>
</p>

<section id="busqueda-rapida">
    <h2>Búsqueda Rápida</h2>

    <form action="resultado_busqueda.php" method="get">
        <?= campoUsuario(); ?>
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
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 3])); ?>">Chalet Independiente con Piscina</a>
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
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 2])); ?>">Estudio Luminoso para Estudiantes</a>
            </h3>

            <img src="img/estudio.jpg"
                 alt="Estudio luminoso para estudiantes en Alicante"
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
                <?= euros(600); ?>/mes |
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
                18/09/2026
            </p>

            <p>
                Estudio completamente equipado, ideal para estudiantes y situado cerca de la universidad
            </p>
        </li>

        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 1])); ?>">Piso Céntrico con Terraza</a>
            </h3>

            <img src="img/piso-terraza.jpg"
                 alt="Piso céntrico con terraza en Jávea"
                 width="150"
                 height="100">

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                Jávea, Alicante, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(150000); ?> |
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
                15/09/2026
            </p>

            <p>
                Piso amplio y luminoso situado en el centro, con una gran terraza
            </p>
        </li>

        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 4])); ?>">Dúplex Elegante con Amplias Estancias</a>
            </h3>

            <img src="img/duplex-valencia.jpg"
                 alt="Dúplex Elegante con Amplias Estancias"
                 width="150"
                 height="100">

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                Sant Francesc, Valencia, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(2300); ?>/mes |
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
                24/07/2026
            </p>

            <p>
                Disfruta de Valencia viviendo en este duplex en una de las zonas más señoriales de la ciudad.
            </p>
        </li>

        <li class="card">
            <h3>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => 5])); ?>">Piso Exclusivo de 4 Habitaciones y 3 Baños</a>
            </h3>

            <img src="img/piso-valencia.jpg"
                 alt="Piso en venta en Passatge de Ripalda, Valencia"
                 width="150"
                 height="100">

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                    Localización
                </b>
                Sant Francesc, Valencia, España
            </p>

            <p>
                <b>
                    <span class="material-symbols-outlined" aria-hidden="true">euro</span>
                    Precio
                </b>
                <?= euros(1400000); ?> |
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
                12/04/2026
            </p>

            <p>
                Échale un vistazo a este piso en Passatge de Ripalda, Sant Francesc, Valencia
            </p>
        </li>

    </ul>
</section>

<?php
// 5. Cargar el cierre modular (main, footer, scripts y cierres HTML)
require_once "includes/layout/pie.php";
?>