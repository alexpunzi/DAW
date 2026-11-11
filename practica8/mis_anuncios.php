<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";

$usuario  = requiereUsuario();
$anuncios = anunciosDeUsuario($usuario['email']);

$title          = "Mis anuncios";
$esPartePrivada = true;

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Mis anuncios</h2>

    <?php if (empty($anuncios)): ?>
        <p>
            Todavía no tienes ningún anuncio.
            <a href="<?= e(urlPrivada('crear_anuncio.php')); ?>">Crea tu primer anuncio</a>.
        </p>
    <?php else: ?>
        <ul class="cards-grid">
            <?php foreach ($anuncios as $a): ?>
                <li class="card">
                    <h3>
                        <a href="<?= e(urlPrivada('ver_anuncio.php', ['id' => $a['id']])); ?>">
                            <?= e($a['titulo']); ?>
                        </a>
                    </h3>

                    <img src="<?= e($a['foto_principal']); ?>"
                        alt="Foto principal de <?= e($a['titulo']); ?>"
                        width="150"
                        height="100">

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                        <?= e($a['ciudad']); ?>, <?= e($a['pais']); ?>
                    </p>

                    <p>
                        <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b>
                        <?= euros($a['precio']); ?>
                    </p>

                    <p>
                        <a href="<?= e(urlPrivada('anadir_foto.php', ['anuncio' => $a['id']])); ?>">
                            <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
                            Añadir foto
                        </a>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php
require_once "includes/layout/pie.php";
?>
