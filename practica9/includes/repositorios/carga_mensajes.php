<?php

function obtenerMensajesDeAnuncio(int $idAnuncio): array
{
    if ($idAnuncio <= 0) {
        return [];
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT m.IdMensaje,
                       m.TMensaje,
                       tm.NomTMensaje AS tipo_mensaje,
                       m.Texto,
                       m.Anuncio,
                       m.Usuario AS id_remitente,
                       u.NomUsuario AS remitente,
                       u.Email AS email_remitente,
                       m.FRegistro
                FROM Mensajes m
                INNER JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje
                INNER JOIN Usuarios u ON m.Usuario = u.IdUsuario
                WHERE m.Anuncio = ?
                ORDER BY m.FRegistro DESC, m.IdMensaje DESC";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $mensajes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $mensajes[] = [
                'id'              => (int)$fila['IdMensaje'],
                'id_tmensaje'     => (int)$fila['TMensaje'],
                'tipo_mensaje'    => $fila['tipo_mensaje'],
                'texto'           => $fila['Texto'],
                'id_anuncio'      => (int)$fila['Anuncio'],
                'id_remitente'    => (int)$fila['id_remitente'],
                'remitente'       => $fila['remitente'],
                'email_remitente' => $fila['email_remitente'],
                'fecha'           => $fila['FRegistro'],
            ];
        }

        $stmt->close();
        return $mensajes;
    } catch (Throwable $e) {
        error_log('Error en obtenerMensajesDeAnuncio: ' . $e->getMessage());
        return [];
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}