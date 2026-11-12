<?php
require_once __DIR__ . '/../lib/funciones.php';

// --- LÓGICA DEL PANEL DE ÚLTIMOS ANUNCIOS VISITADOS ---
$visitadosIds = [];
if (!empty($_COOKIE['ultimos_visitados'])) {
    $raw = json_decode($_COOKIE['ultimos_visitados'], true);
    if (is_array($raw)) {
        foreach ($raw as $val) {
            $vId = leerId($val);
            if ($vId !== null && buscarAnuncio($vId) !== null && !in_array($vId, $visitadosIds, true)) {
                $visitadosIds[] = $vId;
            }
        }
    }
}
$visitadosIds = array_slice($visitadosIds, 0, 4);

// Obtener datos de los anuncios existentes
$anunciosVisitados = [];
foreach ($visitadosIds as $vId) {
    $a = buscarAnuncio($vId);
    if ($a !== null) {
        $anunciosVisitados[] = $a;
    }
}
$anunciosVisitados = array_slice($anunciosVisitados, 0, 4);
?>

    <?php if (!empty($anunciosVisitados)): ?>
        <!-- PANEL DE ÚLTIMOS ANUNCIOS VISITADOS (VISIBLE EN TODAS LAS PÁGINAS) -->
        <section class="ultimos-visitados">
            <h2>Últimos anuncios visitados</h2>
            <ul class="cards-grid">
                <?php foreach ($anunciosVisitados as $a): ?>
                    <li class="card">
                        <h3>
                            <a href="<?= e(urlPrivada('anuncio.php', ['id' => $a['id']])); ?>"><?= e($a['titulo']); ?></a>
                        </h3>
                        <img src="<?= e($a['foto_principal'] ?? $a['foto'] ?? ''); ?>"
                            alt="<?= e($a['titulo']); ?>"
                            width="150"
                            height="100">

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                            <?= e($a['ciudad']); ?>, <?= e($a['provincia'] ?? ''); ?>, <?= e($a['pais']); ?>
                        </p>

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros($a['precio']); ?> |
                            <b><span class="material-symbols-outlined" aria-hidden="true">bed</span> Habitaciones</b> <?= (int)($a['caracteristicas']['habitaciones'] ?? 0); ?> |
                            <b><span class="material-symbols-outlined" aria-hidden="true">bathtub</span> Baños</b> <?= (int)($a['caracteristicas']['banos'] ?? 0); ?>
                        </p>

                        <?php
                        $fechaPublicacion = $a['fecha'] ?? '';
                        $fechaPublicacion = $fechaPublicacion !== '' ? date('d/m/Y', strtotime($fechaPublicacion)) : '';
                        ?>
                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
                            <?= e($fechaPublicacion); ?>
                        </p>

                        <p><?= e($a['descripcion'] ?? ''); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    </main>

    <footer>
        <hr>

        <nav aria-label="Navegación del pie de página">
            <ul>
                <li>
                    <a href="<?= e(urlInicio()); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">home</span>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="<?= e(urlPrivada('buscar.php')); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">search</span>
                        Buscar
                    </a>
                </li>
                <?php if (estaIdentificado()): ?>
                    <li>
                        <a href="<?= e(urlPrivada('solicitar_folleto.php')); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">description</span>
                            Solicitar folleto
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= e(urlPrivada('accesibilidad.php')); ?>">
                        <span class="material-symbols-outlined" aria-hidden="true">accessibility_new</span>
                        Accesibilidad
                    </a>
                </li>
            </ul>
        </nav>

        <p>
            <small>
                Autores: Juana Valentina Echeveste y Alex Punzano Gil<br>
                &copy; <?= date('Y'); ?> PI - Pisos &amp; Inmuebles. El portal de tu vivienda
            </small>
        </p>
    </footer>

</body>
</html>