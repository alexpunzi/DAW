<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../repositorios/carga_estilos.php';

$usuario = requiereUsuario();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('configurar.php');
}

$idEstilo = leerId(post('estilo'));
$estiloSeleccionado = ($idEstilo !== null) ? buscarEstilo($idEstilo) : null;

if ($estiloSeleccionado === null) {
    flash_set('error', 'El estilo seleccionado no es válido.');
    redirigir('configurar.php');
}

//actualizamos la sesión para que cabecera.php cargue el nuevo CSS inmediatamente
$_SESSION['id_estilo'] = $estiloSeleccionado['id'];
$_SESSION['estilo']    = $estiloSeleccionado['fichero'];

//solo con cookies aceptadas ("Recordarme") la preferencia se guarda en el perfil
if (cookiesAceptadas()) {
    try {
        $mysqli = conectar_DB();
        $stmt = $mysqli->prepare("UPDATE Usuarios SET Estilo = ? WHERE IdUsuario = ?");
        $idUsuario = (int)($_SESSION['id_usuario'] ?? $usuario['id']);
        $stmt->bind_param('ii', $estiloSeleccionado['id'], $idUsuario);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {
        error_log('Error al actualizar estilo en BD: ' . $e->getMessage());
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

flash_set('exito', 'Se ha aplicado el estilo "' . $estiloSeleccionado['nombre'] . '".');
redirigir('configurar.php');