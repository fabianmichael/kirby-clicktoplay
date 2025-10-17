<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('fabianmichael/clicktoplay', [
    'options' => [
        'cache' => true,
        'cacheLifetime' => 0, // infinite cache lifetime
    ],

    'snippets' => [
        'clicktoplay' => __DIR__ . '/snippets/clicktoplay.php',
    ],
]);
