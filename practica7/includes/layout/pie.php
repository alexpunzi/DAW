<?php
require_once __DIR__ . '/../lib/funciones.php';
?>
    </main>

    <footer>
        <hr>

        <nav aria-label="Navegación del pie de página">
            <ul>
                <li>
                    <a href="index.php">
                        <span class="material-symbols-outlined" aria-hidden="true">home</span>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="buscar.php">
                        <span class="material-symbols-outlined" aria-hidden="true">search</span>
                        Buscar
                    </a>
                </li>
                <li>
                    <a href="solicitar_folleto.php">
                        <span class="material-symbols-outlined" aria-hidden="true">description</span>
                        Solicitar folleto
                    </a>
                </li>
                <li>
                    <a href="accesibilidad.php">
                        <span class="material-symbols-outlined" aria-hidden="true">accessibility_new</span>
                        Accesibilidad
                    </a>
                </li>
            </ul>
        </nav>

        <p>
            <small>
                Autores: Juana Valentina Echeveste y Alex Punzano Gil<br>
                &copy; <?= date('Y'); ?> PI - Pisos &amp; Inmuebles. El portal de tu vivienda.
            </small>
        </p>
    </footer>

</body>
</html>