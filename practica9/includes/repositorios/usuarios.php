<?php

function cargarUsuarios(): array
{
    $fichero = __DIR__ . '/../../datos/usuarios.php';
    if (!is_readable($fichero)) {
        return [];
    }

    require $fichero;
    return isset($usuariosValidos) && is_array($usuariosValidos)
        ? $usuariosValidos
        : [];
}

function buscarUsuario(string $email): ?array
{
    foreach (cargarUsuarios() as $usuario) {
        if (($usuario['email'] ?? null) === $email) {
            return $usuario;
        }
    }

    return null;
}
