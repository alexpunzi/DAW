<?php


$anunciosFicticios = [
    'impar' => [
        'id'               => 1,
        'titulo'           => 'Chalet Independiente con Piscina y Jardín',
        'precio'           => 320000,
        'ciudad'           => 'Altea',
        'provincia'        => 'Alicante',
        'pais'             => 'España',
        'descripcion'      => 'Espectacular chalet independiente con vista panorámica al mar, piscina privada, amplio jardín y acabados de alta calidad en zona residencial tranquila.',
        'foto_principal'   => 'img/chalet-piscina.jpg',
        'fecha'            => '2026-10-15',
        'tipo_anuncio'     => 'Venta',
        'tipo_vivienda'    => 'Chalet',
        'caracteristicas'  => [
            'superficie'       => 250, // m²
            'habitaciones'     => 4,
            'banos'            => 3,
            'planta'           => 'Chalet 2 plantas',
            'ano_construccion' => 2018
        ],
        'fotos_miniaturas' => [
            'img/chalet-piscina.jpg',
            'img/chalet-jardin.jpg',
            'img/chalet-salon.jpg'
        ],
        'usuario'          => 'ana.lopez@ua.es'
    ],
    'par' => [
        'id'               => 2,
        'titulo'           => 'Ático Luminoso en el Centro Histórico',
        'precio'           => 185000,
        'ciudad'           => 'Alicante',
        'provincia'        => 'Alicante',
        'pais'             => 'España',
        'descripcion'      => 'Ático céntrico totalmente reformado con terraza exterior de 30 m², orientación sur con luz natural todo el día. Listo para entrar a vivir.',
        'foto_principal'   => 'img/atico-centro.jpg',
        'fecha'            => '2026-10-20',
        'tipo_anuncio'     => 'Venta',
        'tipo_vivienda'    => 'Ático',
        'caracteristicas'  => [
            'superficie'       => 95, // m²
            'habitaciones'     => 2,
            'banos'            => 2,
            'planta'           => '5ª planta con ascensor',
            'ano_construccion' => 2015
        ],
        'fotos_miniaturas' => [
            'img/atico-centro.jpg',
            'img/atico-terraza.jpg',
            'img/atico-cocina.jpg'
        ],
        'usuario'          => 'alex@ejemplo.com'
    ]
];