<?php
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'name'             => 'Checker KDS',
    'short_name'       => 'Checker',
    'description'      => 'Kitchen Display System - Checker',
    'start_url'        => './checker.php',
    'display'          => 'standalone',
    'background_color' => '#0A3FCC',
    'theme_color'      => '#1683FF',
    'orientation'      => 'landscape',
    'icons'            => [
        [
            'src'     => './icon-192.png',
            'sizes'   => '192x192',
            'type'    => 'image/png',
            'purpose' => 'any maskable',
        ],
        [
            'src'     => './icon-512.png',
            'sizes'   => '512x512',
            'type'    => 'image/png',
            'purpose' => 'any maskable',
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
