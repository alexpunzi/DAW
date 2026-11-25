<?php
require_once __DIR__ . '/includes/bootstrap.php';

$usuario  = requiereUsuario();
$idUser   = (int)($_SESSION['id_usuario'] ?? $usuario['id'] ?? 0);
$anuncios = anunciosDeUsuario($idUser);

$title          = "Mis anuncios";
$esPartePrivada = true;

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
?>

<section>
    <h2>Mis anuncios (<?= count($anuncios); ?>)</h2>

    <?php if (empty($anuncios)): ?>
        <p>
            Todavía no tienes ningún anuncio.
            <a href="<?= e(urlPrivada('crear_anuncio.php')); ?>">Crea tu primer anuncio</a>.
        </p>
    <?php else: ?>
        <ul class="cards-grid">
            <?php foreach ($anuncios as $a): ?>
                <?php
                $altFoto = !empty($a['alternativo']) ? $a['alternativo'] : 'Foto principal de ' . $a['titulo'];
                ?>
                <li class="card">
                    <h3>
                        <a href="<?= e(urlPrivada('ver_anuncio.php', ['id' => $a['id']])); ?>">
                            <?= e($a['titulo']); ?>
                        </a>
                    </h3>

                    <img src="<?= e($a['foto_principal']); ?>"
                        alt="<?= e($altFoto); ?>"
                        width="150"
                        height="100">

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">sell</span> Tipo</b>
                        <?= e($a['tipo_anuncio']); ?> · <?= e($a['tipo_vivienda']); ?>
                    </p>

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                        <?= e($a['ciudad']); ?>, <?= e($a['pais']); ?>
                    </p>

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b>
                        <?= euros($a['precio']); ?>
                    </p>

                    <p>
                        <a href="<?= e(urlPrivada('ver_fotos.php', ['id' => $a['id']])); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">photo_library</span>
                            Ver fotos
                        </a>
                        &nbsp;|&nbsp;
                        <a href="<?= e(urlPrivada('anadir_foto.php', ['anuncio' => $a['id']])); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
                            Añadir foto
                        </a>

                        <a href="<?= e(urlPrivada('mensaje_anuncio.php', ['id' => $a['id']])); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                            Ver mensajes
                        </a>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php
require_once __DIR__ . "/includes/layout/pie.php";
?>