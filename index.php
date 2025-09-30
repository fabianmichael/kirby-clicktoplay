<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('fabianmichael/clicktoplay', [
    'options' => [
        'cache' => true,
        'cacheLifetime' => 60 * 24,
    ],

    'snippets' => [
        'clicktoplay' => __DIR__ . '/snippets/clicktoplay.php',
    ],
]);
