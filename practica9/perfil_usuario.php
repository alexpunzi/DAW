<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/repositorios/carga_usuarios.php';

$idPerfil = leerId(get('id'));
$perfil   = ($idPerfil !== null) ? buscarUsuarioPorId($idPerfil) : null;

if ($perfil === null) {
    http_response_code(404);
}

$anunciosUsuario = ($perfil !== null) ? anunciosDeUsuario($perfil['id']) : [];

$title          = $perfil ? "Perfil de " . $perfil['nombre'] : "Usuario no encontrado";
$esPartePrivada = estaIdentificado();

require_once __DIR__ . "/includes/layout/cabecera.php";
require_once __DIR__ . "/includes/layout/inicio.php";
?>

<?php if ($perfil === null): ?>
    <section>
        <h2>Usuario no encontrado</h2>
        <p>El perfil de usuario que buscas no existe o el identificador no es válido.</p>
        <p><a href="<?= e(urlInicio()); ?>">Volver a la página de inicio</a></p>
    </section>
<?php else: ?>
    <?php
    $fechaRegistro = !empty($perfil['fregistro'])
        ? date('d/m/Y', strtotime($perfil['fregistro']))
        : 'Fecha no disponible';
    $fotoPerfil = !empty($perfil['foto']) ? $perfil['foto'] : 'img/default.png';
    ?>

    <section class="conBorde perfil-publico">
        <h2>Perfil público de <?= e($perfil['nombre']); ?></h2>

        <img src="<?= e($fotoPerfil); ?>"
             alt="Foto de perfil de <?= e($perfil['nombre']); ?>"
             width="140"
             height="140">

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">person</span>
            <strong>Nombre de usuario:</strong> <?= e($perfil['nombre']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
            <strong>Miembro desde:</strong> <?= e($fechaRegistro); ?>
        </p>
    </section>

    <section>
        <h3>Anuncios publicados por <?= e($perfil['nombre']); ?> (<?= count($anunciosUsuario); ?>)</h3>

        <?php if (empty($anunciosUsuario)): ?>
            <p>Este usuario todavía no ha publicado ningún anuncio.</p>
        <?php else: ?>
            <ul class="cards-grid">
                <?php foreach ($anunciosUsuario as $anuncio): ?>
                    <?php
                    $fechaAnuncio = !empty($anuncio['fecha']) ? date('d/m/Y', strtotime($anuncio['fecha'])) : '';
                    $altFoto      = !empty($anuncio['alternativo']) ? $anuncio['alternativo'] : $anuncio['titulo'];
                    ?>
                    <li class="card">
                        <h4>
                            <a href="<?= e(urlPrivada('anuncio.php', ['id' => $anuncio['id']])); ?>">
                                <?= e($anuncio['titulo']); ?>
                            </a>
                        </h4>

                        <img src="<?= e($anuncio['foto_principal']); ?>"
                             alt="<?= e($altFoto); ?>"
                             width="150"
                             height="100">

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">sell</span></b>
                            <?= e($anuncio['tipo_anuncio']); ?> · <?= e($anuncio['tipo_vivienda']); ?>
                        </p>

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span></b>
                            <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['pais']); ?>
                        </p>

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">euro</span></b>
                            <?= euros($anuncio['precio']); ?>
                        </p>

                        <?php if ($fechaAnuncio !== ''): ?>
                            <p>
                                <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span></b>
                                <?= e($fechaAnuncio); ?>
                            </p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . "/includes/layout/pie.php"; ?>