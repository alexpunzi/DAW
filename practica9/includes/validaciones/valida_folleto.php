<?php

function validarSolicitudFolleto(array $datos): array
{
    $errores = [];
    $campo = static function (string $nombre) use ($datos): string {
        return isset($datos[$nombre]) && is_scalar($datos[$nombre])
            ? trim((string)$datos[$nombre])
            : '';
    };

    foreach ([
        'nombre' => 'El nombre',
        'apellidos' => 'Los apellidos',
        'calle' => 'La calle',
        'numero' => 'El número',
        'puerta' => 'La puerta',
        'localidad' => 'La localidad',
        'provincia' => 'La provincia',
    ] as $nombreCampo => $etiqueta) {
        if ($campo($nombreCampo) === '') {
            $errores[] = $etiqueta . ' es obligatorio.';
        }
    }

    $correo = $campo('correo');
    if ($correo === '') {
        $errores[] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico no tiene un formato válido.';
    }

    $telefono = $campo('telefono');
    if ($telefono !== '' && !preg_match('/^\+?[0-9 ]{9,15}$/', $telefono)) {
        $errores[] = 'El teléfono solo puede contener números, espacios y un + inicial (9 a 15 cifras).';
    }

    if (!preg_match('/^[0-9]{5}$/', $campo('cp'))) {
        $errores[] = 'El código postal debe tener 5 dígitos.';
    }

    if (!array_key_exists($campo('pais'), paises())) {
        $errores[] = 'Debes seleccionar un país válido.';
    }

    if (!in_array($campo('anuncio'), ['1', '2', '3'], true)) {
        $errores[] = 'Debes seleccionar el anuncio a imprimir.';
    }

    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $campo('color_portada'))) {
        $errores[] = 'El color de la portada no es válido.';
    }

    $copias = filter_var(
        $campo('num_copias'),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 99]]
    );
    if ($copias === false) {
        $errores[] = 'El número de copias debe ser un entero entre 1 y 99.';
    }

    $dpi = filter_var(
        $campo('resolucion'),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 150, 'max_range' => 900]]
    );
    if ($dpi === false || $dpi % 150 !== 0) {
        $errores[] = 'La resolución debe estar entre 150 y 900 dpi (de 150 en 150).';
    }

    if (!in_array($campo('tipo_impresion'), ['color', 'blanco_negro'], true)) {
        $errores[] = 'Debes indicar si la impresión es a color o en blanco y negro.';
    }

    if (!in_array($campo('imprimir_precio'), ['si', 'no'], true)) {
        $errores[] = 'Debes indicar si se imprime el precio del anuncio.';
    }

    $fechaRecepcion = $campo('fecha_recepcion');
    if ($fechaRecepcion !== '') {
        $fecha = DateTime::createFromFormat('Y-m-d', $fechaRecepcion);
        if ($fecha === false || $fecha->format('Y-m-d') !== $fechaRecepcion) {
            $errores[] = 'La fecha de recepción no es válida.';
        } elseif ($fechaRecepcion < date('Y-m-d')) {
            $errores[] = 'La fecha de recepción no puede ser anterior a hoy.';
        }
    }

    return $errores;
}
