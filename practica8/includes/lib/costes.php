<?php
//funciones para calcular los costes de impresion que apareceran en las tablas 

require_once __DIR__ . '/funciones.php';


define('COSTE_ENVIO', 10.0);              
define('PRECIO_FOTO_COLOR', 0.50);        
define('PRECIO_FOTO_ALTA_RES', 0.20);    
define('LIMITE_RESOLUCION_BASE', 300); 

function costePaginas(int $paginas): float {
    $bloques = [
        [4, 2.00],
        [10, 1.80],
        [PHP_INT_MAX, 1.60]
    ];

    $total = 0.0;
    $anterior = 0;

    foreach ($bloques as $b) {
        if ($paginas > $anterior) {
            $enBloque = min($paginas, $b[0]) - $anterior;
            $total += $enBloque * $b[1];
        }
        $anterior = $b[0];
    }

    return $total;
}

function calcularCosteFolleto(int $paginas, int $fotos, bool $color, int $dpi): float {
    $coste = COSTE_ENVIO + costePaginas($paginas);

    if ($color) {
        $coste += $fotos * PRECIO_FOTO_COLOR;
    }

    if ($dpi > LIMITE_RESOLUCION_BASE) {
        $coste += $fotos * PRECIO_FOTO_ALTA_RES;
    }

    return $coste;
}


function calcularCosteTotalPedido(int $paginas, int $fotos, bool $color, int $dpi, int $copias): float {
    $copiasValidas = max(1, $copias);
    $unitario = calcularCosteFolleto($paginas, $fotos, $color, $dpi);
    
    return $unitario * $copiasValidas;
}


function obtenerMatrizTarifas(): array {
    $columnas = [
        ['titulo' => 'B/N 150-300 dpi',    'color' => false, 'dpi' => 300],
        ['titulo' => 'B/N 450-900 dpi',    'color' => false, 'dpi' => 450],
        ['titulo' => 'Color 150-300 dpi',  'color' => true,  'dpi' => 300],
        ['titulo' => 'Color 450-900 dpi',  'color' => true,  'dpi' => 450]
    ];

    $filas = [];

    for ($p = 1; $p <= 15; $p++) {
        $fotos = $p * 3; // 3 fotos por página 
        $preciosCol = [];

        foreach ($columnas as $col) {
            $preciosCol[] = calcularCosteFolleto($p, $fotos, $col['color'], $col['dpi']);
        }

        $filas[] = [
            'paginas' => $p,
            'fotos'   => $fotos,
            'precios' => $preciosCol
        ];
    }

    return [
        'columnas' => $columnas,
        'filas'    => $filas
    ];
}