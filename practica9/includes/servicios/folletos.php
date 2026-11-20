<?php

define('COSTE_ENVIO', 10.0);
define('PRECIO_FOTO_COLOR', 0.50);
define('PRECIO_FOTO_ALTA_RES', 0.20);
define('LIMITE_RESOLUCION_BASE', 300);
define('PAGINAS_FOLLETO_DEMO', 5);
define('FOTOS_POR_PAGINA', 3);

function costePaginas(int $paginas): float
{
    $bloques = [
        [4, 2.00],
        [10, 1.80],
        [PHP_INT_MAX, 1.60],
    ];

    $total = 0.0;
    $anterior = 0;

    foreach ($bloques as $bloque) {
        if ($paginas > $anterior) {
            $enBloque = min($paginas, $bloque[0]) - $anterior;
            $total += $enBloque * $bloque[1];
        }
        $anterior = $bloque[0];
    }

    return $total;
}

function calcularCosteFolleto(int $paginas, int $fotos, bool $color, int $dpi): float
{
    $coste = COSTE_ENVIO + costePaginas($paginas);

    if ($color) {
        $coste += $fotos * PRECIO_FOTO_COLOR;
    }

    if ($dpi > LIMITE_RESOLUCION_BASE) {
        $coste += $fotos * PRECIO_FOTO_ALTA_RES;
    }

    return $coste;
}

function calcularCosteTotalPedido(int $paginas, int $fotos, bool $color, int $dpi, int $copias): float
{
    $copiasValidas = max(1, $copias);
    $unitario = calcularCosteFolleto($paginas, $fotos, $color, $dpi);

    return $unitario * $copiasValidas;
}

function obtenerMatrizTarifas(): array
{
    $columnas = [
        ['titulo' => 'B/N 150-300 dpi', 'color' => false, 'dpi' => 300],
        ['titulo' => 'B/N 450-900 dpi', 'color' => false, 'dpi' => 450],
        ['titulo' => 'Color 150-300 dpi', 'color' => true, 'dpi' => 300],
        ['titulo' => 'Color 450-900 dpi', 'color' => true, 'dpi' => 450],
    ];

    $filas = [];
    for ($paginas = 1; $paginas <= 15; $paginas++) {
        $fotos = $paginas * FOTOS_POR_PAGINA;
        $precios = [];

        foreach ($columnas as $columna) {
            $precios[] = calcularCosteFolleto(
                $paginas,
                $fotos,
                $columna['color'],
                $columna['dpi']
            );
        }

        $filas[] = [
            'paginas' => $paginas,
            'fotos' => $fotos,
            'precios' => $precios,
        ];
    }

    return [
        'columnas' => $columnas,
        'filas' => $filas,
    ];
}

function calcularResumenFolleto(int $dpi, bool $color, int $copias): array
{
    $paginas = PAGINAS_FOLLETO_DEMO;
    $fotos = $paginas * FOTOS_POR_PAGINA;
    $unitario = calcularCosteFolleto($paginas, $fotos, $color, $dpi);

    return [
        'paginas' => $paginas,
        'fotos' => $fotos,
        'unitario' => $unitario,
        'total' => calcularCosteTotalPedido($paginas, $fotos, $color, $dpi, $copias),
    ];
}