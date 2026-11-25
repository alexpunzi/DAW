<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Página restringida a usuarios identificados
$usuario         = requiereUsuario();
$idUsuarioActual = (int)($_SESSION['id_usuario'] ?? $usuario['id'] ?? 0);

$id      = leerId(get('id'));
$anuncio = ($id !== null) ? buscarAnuncio($id) : null;

if ($anuncio === null) {
    http_response_code(404);
}

// Comprobamos si el anuncio pertenece al usuario conectado o viene de su zona privada
$esMiAnuncio    = ($anuncio !== null && (int)$anuncio['id_usuario'] === $idUsuarioActual);
$esPartePrivada = $esMiAnuncio || (get('origen') === 'privado');

// Construimos el listado completo de fotos (foto principal + fotos adicionales de la tabla Fotos)
$todasLasFotos = [];
if ($anuncio !== null) {
    if (!empty($anuncio['foto_principal'])) {
        $todasLasFotos[] = [
            'titulo'      => 'Foto principal',
            'ruta'        => $anuncio['foto_principal'],
            'alternativo' => !empty($anuncio['alternativo'])
                ? $anuncio['alternativo']
                : 'Foto principal de ' . $anuncio['titulo'],
        ];
    }

    foreach (($anuncio['fotos'] ?? []) as $fotoExtra) {
        $todasLasFotos[] = [
            'titulo'      => !empty($fotoExtra['titulo']) ? $fotoExtra['titulo'] : 'Foto adicional',
            'ruta'        => $fotoExtra['ruta'],
            'alternativo' => !empty($fotoExtra['alternativo'])
                ? $fotoExtra['alternativo']
                : (!empty($fotoExtra['titulo']) ? $fotoExtra['titulo'] : $anuncio['titulo']),
        ];
    }
}

$totalFotos = count($todasLasFotos);
$title      = $anuncio ? "Fotos de " . $anuncio['titulo'] : "Anuncio no encontrado";

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
?>

<?php if ($anuncio === null): ?>
    <section>
        <h2>Anuncio no encontrado</h2>
        <p>El anuncio del que intentas ver las fotos no existe o el identificador no es válido.</p>
        <p><a href="<?= e(urlInicio()); ?>">Volver a la página de inicio</a></p>
    </section>
<?php else: ?>

    <section>
        <h3>Todas las fotografías (<?= (int)$totalFotos; ?>)</h3>

        <?php if ($totalFotos === 0): ?>
            <p>Este anuncio no tiene fotografías registradas.</p>
        <?php else: ?>
            <ul class="cards-grid">
                <?php foreach ($todasLasFotos as $indice => $foto): ?>
                    <li class="card">
                        <figure>
                            <img src="<?= e($foto['ruta']); ?>"
                                 alt="<?= e($foto['alternativo']); ?>"
                                 width="320"
                                 height="220">
                            <figcaption>
                                <strong><?= ($indice + 1); ?>. <?= e($foto['titulo']); ?></strong>
                            </figcaption>
                        </figure>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="conBorde">
        <h2>Galería de fotos del anuncio</h2>
        <h3><?= e($anuncio['titulo']); ?></h3>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">sell</span>
            <strong>Tipo:</strong> <?= e($anuncio['tipo_anuncio']); ?> · <?= e($anuncio['tipo_vivienda']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
            <strong>Localización:</strong> <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['pais']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">euro</span>
            <strong>Precio:</strong> <?= euros($anuncio['precio']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">photo_library</span>
            <strong>Total de fotos:</strong> <?= (int)$totalFotos; ?>
        </p>

        <p>
            <?php if ($esMiAnuncio): ?>
                <a href="<?= e(urlPrivada('mis_anuncios.php')); ?>">
                    &larr; Volver a Mis anuncios
                </a>
                &nbsp;|&nbsp;
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => $id])); ?>">
                    Ver detalle del anuncio
                </a>
                &nbsp;|&nbsp;
                <a href="<?= e(urlPrivada('anyadir_foto.php', ['id' => $id])); ?>">
                    <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
                    Añadir foto
                </a>
            <?php else: ?>
                <a href="<?= e(urlPrivada('anuncio.php', ['id' => $id])); ?>">
                    &larr; Volver al detalle del anuncio
                </a>
            <?php endif; ?>
        </p>
    </section>

    
<?php endif; ?>

<?php require_once __DIR__ . "/includes/layout/pie.php"; ?>