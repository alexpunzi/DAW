<?php

function buscarAnuncio(int $id): ?array
{
    if ($id <= 0) {
        return null;
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT a.IdAnuncio,
                       a.TAnuncio,
                       ta.NomTAnuncio AS tipo_anuncio,
                       a.TVivienda,
                       tv.NomTVivienda AS tipo_vivienda,
                       a.FPrincipal,
                       a.Alternativo,
                       a.Titulo,
                       a.Precio,
                       a.Texto,
                       a.Ciudad,
                       a.Pais AS id_pais,
                       p.NomPais AS pais,
                       a.Superficie,
                       a.NHabitaciones,
                       a.NBanyos,
                       a.Planta,
                       a.Anyo,
                       a.FRegistro,
                       a.Usuario AS id_usuario,
                       u.NomUsuario AS usuario
                FROM Anuncios a
                INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
                INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
                INNER JOIN Paises p ON a.Pais = p.IdPais
                INNER JOIN Usuarios u ON a.Usuario = u.IdUsuario
                WHERE a.IdAnuncio = ?
                LIMIT 1";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        if (!$fila) {
            return null;
        }

        // Fotos adicionales de la tabla Fotos
        $sqlFotos = "SELECT IdFoto, Titulo, Foto, Alternativo
                     FROM Fotos
                     WHERE Anuncio = ?
                     ORDER BY IdFoto ASC";

        $stmtFotos = $mysqli->prepare($sqlFotos);
        $stmtFotos->bind_param('i', $id);
        $stmtFotos->execute();
        $resFotos = $stmtFotos->get_result();

        $miniaturas = [];
        $fotosDetalle = [];
        while ($f = $resFotos->fetch_assoc()) {
            $miniaturas[] = $f['Foto'];
            $fotosDetalle[] = [
                'id'          => (int)$f['IdFoto'],
                'titulo'      => $f['Titulo'],
                'ruta'        => $f['Foto'],
                'alternativo' => $f['Alternativo'],
            ];
        }
        $stmtFotos->close();

        return [
            'id'               => (int)$fila['IdAnuncio'],
            'id_tanuncio'      => (int)$fila['TAnuncio'],
            'tipo_anuncio'     => $fila['tipo_anuncio'],
            'id_tvivienda'     => (int)$fila['TVivienda'],
            'tipo_vivienda'    => $fila['tipo_vivienda'],
            'foto_principal'   => $fila['FPrincipal'],
            'alternativo'      => $fila['Alternativo'],
            'titulo'           => $fila['Titulo'],
            'precio'           => (float)$fila['Precio'],
            'descripcion'      => $fila['Texto'],
            'ciudad'           => $fila['Ciudad'],
            'provincia'        => '', // La tabla Anuncios no tiene columna Provincia
            'id_pais'          => (int)$fila['id_pais'],
            'pais'             => $fila['pais'],
            'fecha'            => $fila['FRegistro'],
            'id_usuario'       => (int)$fila['id_usuario'],
            'usuario'          => $fila['usuario'],
            'fotos_miniaturas' => $miniaturas,
            'fotos'            => $fotosDetalle,
            'caracteristicas'  => [
                'superficie'       => $fila['Superficie'] !== null ? (int)$fila['Superficie'] : 0,
                'habitaciones'     => $fila['NHabitaciones'] !== null ? (int)$fila['NHabitaciones'] : 0,
                'banos'            => $fila['NBanyos'] !== null ? (int)$fila['NBanyos'] : 0,
                'planta'           => $fila['Planta'] !== null ? (string)$fila['Planta'] : '',
                'ano_construccion' => $fila['Anyo'] !== null ? (int)$fila['Anyo'] : 0,
            ],
        ];
    } catch (Throwable $e) {
        error_log('Error en buscarAnuncio: ' . $e->getMessage());
        return null;
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

function obtenerUltimosAnuncios(int $limite = 5): array
{
    if ($limite <= 0) {
        return [];
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT a.IdAnuncio,
                       a.TAnuncio,
                       ta.NomTAnuncio AS tipo_anuncio,
                       a.TVivienda,
                       tv.NomTVivienda AS tipo_vivienda,
                       a.FPrincipal,
                       a.Alternativo,
                       a.Titulo,
                       a.Precio,
                       a.Texto,
                       a.Ciudad,
                       a.Pais AS id_pais,
                       p.NomPais AS pais,
                       a.NHabitaciones,
                       a.NBanyos,
                       a.FRegistro,
                       a.Usuario AS id_usuario,
                       u.NomUsuario AS usuario
                FROM Anuncios a
                INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
                INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
                INNER JOIN Paises p ON a.Pais = p.IdPais
                INNER JOIN Usuarios u ON a.Usuario = u.IdUsuario
                ORDER BY a.FRegistro DESC, a.IdAnuncio DESC
                LIMIT ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $anuncios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $anuncios[] = [
                'id'             => (int)$fila['IdAnuncio'],
                'id_tanuncio'    => (int)$fila['TAnuncio'],
                'tipo_anuncio'   => $fila['tipo_anuncio'],
                'id_tvivienda'   => (int)$fila['TVivienda'],
                'tipo_vivienda'  => $fila['tipo_vivienda'],
                'foto_principal' => $fila['FPrincipal'],
                'alternativo'    => $fila['Alternativo'],
                'titulo'         => $fila['Titulo'],
                'precio'         => (float)$fila['Precio'],
                'descripcion'    => $fila['Texto'],
                'ciudad'         => $fila['Ciudad'],
                'id_pais'        => (int)$fila['id_pais'],
                'pais'           => $fila['pais'],
                'habitaciones'   => $fila['NHabitaciones'] !== null ? (int)$fila['NHabitaciones'] : 0,
                'banos'          => $fila['NBanyos'] !== null ? (int)$fila['NBanyos'] : 0,
                'fecha'          => $fila['FRegistro'],
                'id_usuario'     => (int)$fila['id_usuario'],
                'usuario'        => $fila['usuario'],
            ];
        }

        $stmt->close();
        return $anuncios;
    } catch (Throwable $e) {
        echo '<p style="color:red">Error SQL: ' . htmlspecialchars($e->getMessage()) . '</p>';
        error_log('Error en obtenerUltimosAnuncios: ' . $e->getMessage());
        return [];
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

function anunciosDeUsuario(int $idUsuario): array
{
    if ($idUsuario <= 0) {
        return [];
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT a.IdAnuncio,
                       ta.NomTAnuncio AS tipo_anuncio,
                       tv.NomTVivienda AS tipo_vivienda,
                       a.FPrincipal,
                       a.Alternativo,
                       a.Titulo,
                       a.Precio,
                       a.Ciudad,
                       p.NomPais AS pais,
                       a.FRegistro
                FROM Anuncios a
                INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
                INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
                INNER JOIN Paises p ON a.Pais = p.IdPais
                WHERE a.Usuario = ?
                ORDER BY a.FRegistro DESC, a.IdAnuncio DESC";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $anuncios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $anuncios[] = [
                'id'             => (int)$fila['IdAnuncio'],
                'tipo_anuncio'   => $fila['tipo_anuncio'],
                'tipo_vivienda'  => $fila['tipo_vivienda'],
                'foto_principal' => $fila['FPrincipal'],
                'alternativo'    => $fila['Alternativo'],
                'titulo'         => $fila['Titulo'],
                'precio'         => (float)$fila['Precio'],
                'ciudad'         => $fila['Ciudad'],
                'pais'           => $fila['pais'],
                'fecha'          => $fila['FRegistro'],
            ];
        }

        $stmt->close();
        return $anuncios;
    } catch (Throwable $e) {
        error_log('Error en anunciosDeUsuario: ' . $e->getMessage());
        return [];
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}

function parsearBusquedaRapida(string $texto): array
{

    $texto = trim(mb_strtolower($texto, 'UTF-8'));
    $texto = preg_replace('/[.,;:¿?¡!]+/u', ' ', $texto) ?? $texto;

    if ($texto === '') {
        return [
            'tipo_vivienda' => null,
            'tipo_anuncio'  => null,
            'ciudad'        => null,
        ];
    }

    $palabras  = preg_split('/\s+/u', $texto, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $stopWords = ['un', 'una', 'en', 'de'];

    $tokens = [];
    foreach ($palabras as $palabra) {
        if (!in_array($palabra, $stopWords, true)) {
            $tokens[] = $palabra;
        }
    }

    if (empty($tokens)) {
        return [
            'tipo_vivienda' => null,
            'tipo_anuncio'  => null,
            'ciudad'        => null,
        ];
    }

    if (count($tokens) >= 2 && $tokens[0] === 'obra' && $tokens[1] === 'nueva') {
        $tipoVivienda = 'obra nueva';
        $resto = array_slice($tokens, 2);
    } else {
        $tipoVivienda = $tokens[0];
        $resto = array_slice($tokens, 1);
    }

    $tipoAnuncio = $resto[0] ?? null;

    $ciudad = count($resto) > 1
        ? implode(' ', array_slice($resto, 1))
        : null;

    return [
        'tipo_vivienda' => $tipoVivienda,
        'tipo_anuncio'  => $tipoAnuncio,
        'ciudad'        => $ciudad,
    ];
}

function buscarAnuncios(array $filtros): array
{
    // 1. Si viene desde la búsqueda rápida ('texto_busqueda' o 'q'), parseamos en orden fijo
    $textoRapido = trim((string)($filtros['texto_busqueda'] ?? $filtros['q'] ?? ''));
    if ($textoRapido !== '') {
        $rapida = parsearBusquedaRapida($textoRapido);
        $filtros['tipo_vivienda'] = $rapida['tipo_vivienda'];
        $filtros['tipo_anuncio']  = $rapida['tipo_anuncio'];
        $filtros['ciudad']        = $rapida['ciudad'];
    }

    try {
        $mysqli = conectar_DB();

        $sql = "SELECT a.IdAnuncio,
                       a.TAnuncio,
                       ta.NomTAnuncio AS tipo_anuncio,
                       a.TVivienda,
                       tv.NomTVivienda AS tipo_vivienda,
                       a.FPrincipal,
                       a.Alternativo,
                       a.Titulo,
                       a.Precio,
                       a.Texto,
                       a.Ciudad,
                       a.Pais AS id_pais,
                       p.NomPais AS pais,
                       a.NHabitaciones,
                       a.NBanyos,
                       a.FRegistro,
                       a.Usuario AS id_usuario,
                       u.NomUsuario AS usuario
                FROM Anuncios a
                INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
                INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
                INNER JOIN Paises p ON a.Pais = p.IdPais
                INNER JOIN Usuarios u ON a.Usuario = u.IdUsuario
                WHERE 1=1";

        $condiciones = [];
        $tipos = '';
        $params = [];

        // 1. Tipo de vivienda (admite IdTVivienda, 'obra_nueva' o 'Obra nueva', insensible a mayúsculas)
        $tv = trim((string)($filtros['tipo_vivienda'] ?? ''));
        if ($tv !== '') {
            if (ctype_digit($tv)) {
                $condiciones[] = "a.TVivienda = ?";
                $tipos .= 'i';
                $params[] = (int)$tv;
            } else {
                $tvNormalizado = str_replace('_', ' ', $tv);
                $condiciones[] = "LOWER(tv.NomTVivienda) = LOWER(?)";
                $tipos .= 's';
                $params[] = $tvNormalizado;
            }
        }

        // 2. Tipo de anuncio (admite IdTAnuncio o nombre insensible a mayúsculas)
        $ta = trim((string)($filtros['tipo_anuncio'] ?? ''));
        if ($ta !== '') {
            if (ctype_digit($ta)) {
                $condiciones[] = "a.TAnuncio = ?";
                $tipos .= 'i';
                $params[] = (int)$ta;
            } else {
                $condiciones[] = "LOWER(ta.NomTAnuncio) = LOWER(?)";
                $tipos .= 's';
                $params[] = $ta;
            }
        }

        // 3. Ciudad (insensible a mayúsculas y minúsculas)
        $ciudad = trim((string)($filtros['ciudad'] ?? ''));
        if ($ciudad !== '') {
            $condiciones[] = "LOWER(a.Ciudad) LIKE LOWER(?)";
            $tipos .= 's';
            $params[] = '%' . $ciudad . '%';
        }

        // 4. País (admite IdPais numérico o slug/nombre como 'espana', 'argentina', 'mexico')
        $pais = trim((string)($filtros['pais'] ?? ''));
        if ($pais !== '') {
            if (ctype_digit($pais) && (int)$pais > 0) {
                $condiciones[] = "a.Pais = ?";
                $tipos .= 'i';
                $params[] = (int)$pais;
            } else {
                $mapaPaises = [
                    'espana'    => 'España',
                    'argentina' => 'Argentina',
                    'mexico'    => 'México',
                ];
                $nombrePais = $mapaPaises[mb_strtolower($pais, 'UTF-8')] ?? $pais;
                $condiciones[] = "LOWER(p.NomPais) = LOWER(?)";
                $tipos .= 's';
                $params[] = $nombrePais;
            }
        }

        // 5. Precio desde y precio hasta
        $precioDesde = trim((string)($filtros['precio_desde'] ?? $filtros['precio_min'] ?? ''));
        if ($precioDesde !== '' && is_numeric($precioDesde)) {
            $condiciones[] = "a.Precio >= ?";
            $tipos .= 'd';
            $params[] = (float)$precioDesde;
        }

        $precioHasta = trim((string)($filtros['precio_hasta'] ?? $filtros['precio_max'] ?? ''));
        if ($precioHasta !== '' && is_numeric($precioHasta)) {
            $condiciones[] = "a.Precio <= ?";
            $tipos .= 'd';
            $params[] = (float)$precioHasta;
        }

        // 6. Fecha de publicación desde y hasta
        $fechaDesde = trim((string)($filtros['fecha_desde'] ?? ''));
        if ($fechaDesde !== '') {
            $condiciones[] = "DATE(a.FRegistro) >= ?";
            $tipos .= 's';
            $params[] = $fechaDesde;
        }

        $fechaHasta = trim((string)($filtros['fecha_hasta'] ?? ''));
        if ($fechaHasta !== '') {
            $condiciones[] = "DATE(a.FRegistro) <= ?";
            $tipos .= 's';
            $params[] = $fechaHasta;
        }

        if (!empty($condiciones)) {
            $sql .= ' AND ' . implode(' AND ', $condiciones);
        }

        $sql .= ' ORDER BY a.FRegistro DESC, a.IdAnuncio DESC';

        $stmt = $mysqli->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $anuncios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $anuncios[] = [
                'id'             => (int)$fila['IdAnuncio'],
                'id_tanuncio'    => (int)$fila['TAnuncio'],
                'tipo_anuncio'   => $fila['tipo_anuncio'],
                'id_tvivienda'   => (int)$fila['TVivienda'],
                'tipo_vivienda'  => $fila['tipo_vivienda'],
                'foto_principal' => $fila['FPrincipal'],
                'alternativo'    => $fila['Alternativo'],
                'titulo'         => $fila['Titulo'],
                'precio'         => (float)$fila['Precio'],
                'descripcion'    => $fila['Texto'],
                'ciudad'         => $fila['Ciudad'],
                'id_pais'        => (int)$fila['id_pais'],
                'pais'           => $fila['pais'],
                'habitaciones'   => $fila['NHabitaciones'] !== null ? (int)$fila['NHabitaciones'] : 0,
                'banos'          => $fila['NBanyos'] !== null ? (int)$fila['NBanyos'] : 0,
                'fecha'          => $fila['FRegistro'],
                'id_usuario'     => (int)$fila['id_usuario'],
                'usuario'        => $fila['usuario'],
            ];
        }

        $stmt->close();
        return $anuncios;
    } catch (Throwable $e) {
        error_log('Error en buscarAnuncios: ' . $e->getMessage());
        return [];
    } finally {
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}