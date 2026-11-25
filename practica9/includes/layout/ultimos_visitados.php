<?php
// Panel "Últimos anuncios visitados": solo en la página de inicio y solo con cookies aceptadas
$anunciosVisitados = [];
foreach (leerVisitados() as $vId) {
    $anuncio = buscarAnuncio($vId);
    if ($anuncio !== null) {
        $anunciosVisitados[] = $anuncio;
    }
}
?>

    <?php if (!empty($anunciosVisitados)): ?>
        <!-- PANEL DE ÚLTIMOS ANUNCIOS VISITADOS -->
        <section class="ultimos-visitados">
            <h2>Últimos anuncios visitados</h2>
            <ul class="cards-grid">
                <?php foreach ($anunciosVisitados as $a): ?>
                    <li class="card">
                        <h3>
                            <a href="<?= e(urlPrivada('anuncio.php', ['id' => $a['id']])); ?>"><?= e($a['titulo']); ?></a>
                        </h3>
                        <img src="<?= e($a['foto_principal'] ?? $a['foto'] ?? ''); ?>"
                            alt="<?= e($a['alternativo'] ?? $a['titulo']); ?>"
                            width="150"
                            height="100">

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                            <?= e($a['ciudad']); ?><?= !empty($a['provincia']) ? ', ' . e($a['provincia']) : ''; ?>, <?= e($a['pais']); ?>
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

