<?php

/**
 * PHPUnit bootstrap for the pixelopen/kirby-uikit-builder plugin.
 *
 * Boots Kirby once for the whole suite, on Fixture's temporary tree. Tests
 * reach the instance through TestCase::$kirby.
 */

$autoload = __DIR__ . '/../vendor/autoload.php';

if (is_file($autoload) === false) {
    fwrite(STDERR, "Dépendances absentes. Lancer d'abord : composer install\n");
    exit(1);
}

require $autoload;

PixelOpen\KirbyUikitBuilder\Tests\Fixture::kirby();
