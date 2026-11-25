
<?php

function buscarUsuario(string $identificador): ?array
{
    $identificador = trim($identificador);
    if ($identificador === '') {
        return null;
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT u.IdUsuario,
                       u.NomUsuario,
                       u.Clave,
                       u.Email,
                       u.Sexo,
                       u.FNacimiento,
                       u.Ciudad,
                       u.Pais,
                       u.Foto,
                       u.FRegistro,
                       u.Estilo AS id_estilo,
                       e.Nombre AS nombre_estilo,
                       e.Fichero AS fichero_estilo
                FROM Usuarios u
                LEFT JOIN Estilos e ON u.Estilo = e.IdEstilo
                WHERE u.Email = ? OR u.NomUsuario = ?
                LIMIT 1";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ss', $identificador, $identificador);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        if (!$fila) {
            return null;
        }

        return [
            'id'             => (int)$fila['IdUsuario'],
            'nombre'         => $fila['NomUsuario'],
            'password'       => $fila['Clave'],
            'email'          => $fila['Email'],
            'sexo'           => $fila['Sexo'],
            'fnacimiento'    => $fila['FNacimiento'],
            'ciudad'         => $fila['Ciudad'],
            'pais'           => $fila['Pais'],
            'foto'           => $fila['Foto'],
            'fregistro'      => $fila['FRegistro'],
            'id_estilo'      => $fila['id_estilo'] !== null ? (int)$fila['id_estilo'] : null,
            'estilo'         => $fila['fichero_estilo'] ?? null,
        ];
    } catch (Throwable $e) {
        error_log('Error en buscarUsuario: ' . $e->getMessage());
        return null;
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

function buscarUsuarioPorId(int $idUsuario): ?array
{
    if ($idUsuario <= 0) {
        return null;
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT IdUsuario,
                       NomUsuario,
                       Email,
                       Sexo,
                       FNacimiento,
                       Ciudad,
                       Pais,
                       Foto,
                       FRegistro
                FROM Usuarios
                WHERE IdUsuario = ?
                LIMIT 1";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        if (!$fila) {
            return null;
        }

        return [
            'id'          => (int)$fila['IdUsuario'],
            'nombre'      => $fila['NomUsuario'],
            'email'       => $fila['Email'],
            'sexo'        => $fila['Sexo'],
            'fnacimiento' => $fila['FNacimiento'],
            'ciudad'      => $fila['Ciudad'],
            'pais'        => $fila['Pais'],
            'foto'        => $fila['Foto'],
            'fregistro'   => $fila['FRegistro'],
        ];
    } catch (Throwable $e) {
        error_log('Error en buscarUsuarioPorId: ' . $e->getMessage());
        return null;
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

//No es neceario en esta practica pero me da toc no hacerlo :D 
function existeUsuarioOEmail(string $nomUsuario, string $email): ?string
{
    try {
        $mysqli = conectar_DB();
        $sql = "SELECT NomUsuario, Email
                FROM Usuarios
                WHERE NomUsuario = ? OR Email = ?
                LIMIT 1";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ss', $nomUsuario, $email);
        $stmt->execute();
        $res  = $stmt->get_result();
        $fila = $res->fetch_assoc();
        $stmt->close();

        if (!$fila) {
            return null;
        }
        if (mb_strtolower($fila['NomUsuario']) === mb_strtolower($nomUsuario)) {
            return 'El nombre de usuario "' . $nomUsuario . '" ya está en uso.';
        }
        return 'El correo electrónico "' . $email . '" ya está registrado.';
    } catch (Throwable $e) {
        error_log('Error en existeUsuarioOEmail: ' . $e->getMessage());
        return 'Error al comprobar si el usuario ya existe.';
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

//funcion para el registro de usuarios 
// funcion para el registro de usuarios 
function registrarUsuario(array $datos): ?int
{
    try {
        $mysqli = conectar_DB();

        $clavePlana  = (string)($datos['password'] ?? $datos['clave'] ?? '');
        $claveHash   = password_hash($clavePlana, PASSWORD_DEFAULT);

        $sexo        = (int)($datos['sexo'] ?? 0);

        $fechaRaw    = trim((string)($datos['fecha_nacimiento'] ?? $datos['fnacimiento'] ?? ''));
        $fnacimiento = $fechaRaw !== '' ? $fechaRaw : null;

        $ciudad      = trim((string)($datos['ciudad'] ?? ''));

        $paisRaw     = trim((string)($datos['pais'] ?? ''));
        $pais        = ($paisRaw !== '' && ctype_digit($paisRaw) && (int)$paisRaw > 0)
            ? (int)$paisRaw
            : null;

        if ($pais === null) {
            $resPais  = $mysqli->query("SELECT IdPais FROM Paises ORDER BY IdPais ASC LIMIT 1");
            $filaPais = $resPais ? $resPais->fetch_assoc() : null;
            $pais     = $filaPais ? (int)$filaPais['IdPais'] : null;
        }

        $foto   = !empty($datos['foto']) ? $datos['foto'] : 'img/default.png';
        $estilo = 1;

        $sql = "INSERT INTO Usuarios
                    (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, Estilo, FRegistro)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            'sssissisi',
            $datos['nombre'],
            $claveHash,
            $datos['email'],
            $sexo,
            $fnacimiento,
            $ciudad,
            $pais,
            $foto,
            $estilo
        );

        $ok      = $stmt->execute();
        $nuevoId = $ok ? (int)$mysqli->insert_id : null;
        $stmt->close();

        return $nuevoId;
    } catch (Throwable $e) {
        error_log('Error en registrarUsuario: ' . $e->getMessage());
        return null;
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}