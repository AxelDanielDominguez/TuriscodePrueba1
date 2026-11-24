<?php
// id => slug por idioma. 'nav' = aparece en el menú. Para agregar una página: una línea acá + un archivo en app/views/pages/<id>.php
return [
    'pages' => [
        'home'       => ['es' => '',              'en' => '',           'nav' => false],
        'rooms'      => ['es' => 'habitaciones',  'en' => 'rooms',      'nav' => true],
        'services'   => ['es' => 'servicios',     'en' => 'services',   'nav' => true],
        'restaurant' => ['es' => 'restaurante',   'en' => 'restaurant', 'nav' => false],
        'pool'       => ['es' => 'pileta',        'en' => 'pool',       'nav' => false],
        'spa'        => ['es' => 'spa',           'en' => 'spa',        'nav' => true],
        'location'   => ['es' => 'ubicacion',     'en' => 'location',   'nav' => true],
        'rates'      => ['es' => 'tarifas',       'en' => 'rates',      'nav' => true],
        'gallery'    => ['es' => 'galeria',       'en' => 'gallery',    'nav' => true],
        'contact'    => ['es' => 'contacto',      'en' => 'contact',    'nav' => true],
    ],
    // URLs del sitio viejo -> redirección 301 (conserva el posicionamiento actual)
    'legacy' => [
        '/servicio' => 'services', '/instalacion/habitacion' => 'rooms', '/instalacion/restaurant' => 'restaurant',
        '/instalacion/pileta' => 'pool', '/tarifa' => 'rates', '/galeria' => 'gallery',
        '/ubicacion' => 'location', '/contacto' => 'contact',
    ],
];
