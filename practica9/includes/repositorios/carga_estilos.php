<?php

function obtenerEstilos(): array
{
    try {
        $mysqli = conectar_DB();
        $sql = "SELECT IdEstilo, Nombre, Descripcion, Fichero FROM Estilos ORDER BY IdEstilo ASC";
        $resultado = $mysqli->query($sql);

        $estilos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $estilos[] = [
                'id'          => (int)$fila['IdEstilo'],
                'nombre'      => $fila['Nombre'],
                'descripcion' => $fila['Descripcion'],
                'fichero'     => $fila['Fichero'],
            ];
        }
        $resultado->free();
        return $estilos;
    } catch (Throwable $e) {
        error_log('Error en obtenerEstilos: ' . $e->getMessage());
        return [];
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

function buscarEstilo(int $idEstilo): ?array
{
    if ($idEstilo <= 0) {
        return null;
    }

    try {
        $mysqli = conectar_DB();
        $sql = "SELECT IdEstilo, Nombre, Descripcion, Fichero FROM Estilos WHERE IdEstilo = ? LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idEstilo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        if (!$fila) {
            return null;
        }

        return [
            'id'          => (int)$fila['IdEstilo'],
            'nombre'      => $fila['Nombre'],
            'descripcion' => $fila['Descripcion'],
            'fichero'     => $fila['Fichero'],
        ];
    } catch (Throwable $e) {
        error_log('Error en buscarEstilo: ' . $e->getMessage());
        return null;
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}