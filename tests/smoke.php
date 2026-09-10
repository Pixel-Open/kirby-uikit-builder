<?php

/**
 * Standalone smoke test for the pixelopen/kirby-uikit-builder plugin.
 *
 * Unlike the showcase repository's smoke test, this one assumes no site around
 * the plugin: Fixture installs a minimal Kirby tree in a temporary folder,
 * links the plugin into it the way site/plugins/ would, and boots Kirby. The
 * same fixture serves as the PHPUnit bootstrap.
 *
 * Checks:
 *   1. that the plugin is registered;
 *   2. that every block/field blueprint resolves to a non-empty array;
 *   3. blueprint / snippet consistency for blocks;
 *   4. that no pixelopen.kirby-uikit-builder.* translation key is left unresolved;
 *   5. key parity between translations/fr.php and translations/en.php;
 *   6. that the plugin's PSR-4 classes are autoloadable;
 *   7. that every block has an icon that exists in the Panel sprite;
 *   8. that every block is offered in the fieldsets of fields/layout.yml;
 *   9. that every block has its doc page and its line in doc/index.md.
 *
 * Usage: composer install && php tests/smoke.php   (exit 0 = pass, exit 1 = fail)
 */

use Kirby\Data\Data;
use PixelOpen\KirbyUikitBuilder\Tests\Fixture;

$plugin   = dirname(__DIR__);
$autoload = $plugin . '/vendor/autoload.php';

if (is_file($autoload) === false) {
    fwrite(STDERR, "Dépendances absentes. Lancer d'abord : composer install\n");
    exit(1);
}

require $autoload;

$kirby = Fixture::kirby();

$errors = [];
$passed = 0;
$check  = function (bool $ok, string $label) use (&$errors, &$passed) {
    if ($ok) {
        $passed++;
    } else {
        $errors[] = $label;
        echo "  ✗ $label\n";
    }
};

// 1. Plugin registered
$check($kirby->plugin('pixelopen/kirby-uikit-builder') !== null, 'plugin pixelopen/kirby-uikit-builder enregistré');

// 2. Resolution of every blueprint in the plugin
$extensions = $kirby->extensions('blueprints');
$unresolved = [];

$blueprintFiles = array_merge(
    glob($plugin . '/blueprints/blocks/*.yml'),
    glob($plugin . '/blueprints/fields/*.yml'),
);

foreach ($blueprintFiles as $file) {
    $name = basename(dirname($file)) . '/' . basename($file, '.yml');
    $ext  = $extensions[$name] ?? null;
    $check($ext !== null, "blueprint $name enregistré");
    if ($ext === null) {
        continue;
    }

    $resolved = is_callable($ext) ? $ext() : $ext;
    $check(is_array($resolved) && $resolved !== [], "blueprint $name se résout en tableau non vide");

    // 4. Any string still prefixed pixelopen.kirby-uikit-builder.* after resolution
    //    is a missing translation key (t() returns the key as a fallback).
    if (is_array($resolved)) {
        array_walk_recursive($resolved, function ($value) use (&$unresolved, $name) {
            if (is_string($value) && str_starts_with($value, 'pixelopen.kirby-uikit-builder.')) {
                $unresolved["$name : $value"] = true;
            }
        });
    }
}

$check($unresolved === [], 'aucune clé de traduction non résolue' . ($unresolved ? ' (' . implode(', ', array_keys($unresolved)) . ')' : ''));

// 3. Blueprint / snippet consistency for blocks
$snippets = $kirby->extensions('snippets');
foreach (glob($plugin . '/blueprints/blocks/*.yml') as $file) {
    $block = basename($file, '.yml');
    $check(isset($snippets['blocks/' . $block]), "snippet blocks/$block présent pour son blueprint");
}
foreach (glob($plugin . '/snippets/blocks/*.php') as $file) {
    $block = basename($file, '.php');
    $check(isset($extensions['blocks/' . $block]), "blueprint blocks/$block présent pour son snippet");
}

// 5. fr / en translation key parity
$fr = require $plugin . '/translations/fr.php';
$en = require $plugin . '/translations/en.php';
$missingEn = array_keys(array_diff_key($fr, $en));
$missingFr = array_keys(array_diff_key($en, $fr));
$check($missingEn === [], 'clés fr toutes présentes en en' . ($missingEn ? ' (manquantes : ' . implode(', ', $missingEn) . ')' : ''));
$check($missingFr === [], 'clés en toutes présentes en fr' . ($missingFr ? ' (manquantes : ' . implode(', ', $missingFr) . ')' : ''));

// 6. The plugin's PSR-4 classes
foreach (glob($plugin . '/src/*.php') as $file) {
    $class = 'PixelOpen\\KirbyUikitBuilder\\' . basename($file, '.php');
    $check(class_exists($class), "classe $class autoloadable");
}

// 7. Block icons: defined and present in the Panel sprite
$sprite = $plugin . '/vendor/getkirby/cms/panel/dist/img/icons.svg';
$panelIcons = [];
if (is_file($sprite) && preg_match_all('/id="icon-([a-z0-9-]+)"/', file_get_contents($sprite), $m)) {
    $panelIcons = array_flip($m[1]);
}
$check($panelIcons !== [], 'sprite d\'icônes du Panel lisible');

$icons = [];
foreach (glob($plugin . '/blueprints/blocks/*.yml') as $file) {
    $block = basename($file, '.yml');
    $icon  = Data::read($file)['icon'] ?? null;
    $check(is_string($icon) && $icon !== '', "bloc $block : icône définie");
    if ($panelIcons && is_string($icon) && $icon !== '') {
        $check(isset($panelIcons[$icon]), "bloc $block : icône « $icon » existe dans le Panel");
        $icons[$icon][] = $block;
    }
}
foreach ($icons as $icon => $blocks) {
    $check(count($blocks) === 1, "icône « $icon » unique (partagée par : " . implode(', ', $blocks) . ')');
}

// 8. Every block is offered in the fieldsets of fields/layout.yml.
//    A block missing from that list is valid but invisible in the Panel.
$layout    = Data::read($plugin . '/blueprints/fields/layout.yml');
$offered   = [];
foreach ($layout['fieldsets'] ?? [] as $group) {
    foreach ((array)($group['fieldsets'] ?? []) as $name) {
        $offered[$name] = true;
    }
}
foreach (glob($plugin . '/blueprints/blocks/*.yml') as $file) {
    $block = basename($file, '.yml');
    if ($block === 'column-options') {
        continue; // layout fieldset, not a content block
    }
    $check(isset($offered[$block]), "bloc $block proposé dans les fieldsets de fields/layout.yml");
}

// 9. Documentation coverage
$index = file_get_contents($plugin . '/doc/index.md');
foreach (glob($plugin . '/blueprints/blocks/*.yml') as $file) {
    $block = basename($file, '.yml');
    if ($block === 'column-options') {
        continue;
    }
    $check(is_file($plugin . "/doc/blocks/$block.md"), "doc/blocks/$block.md présente");
    $check(str_contains($index, "blocks/$block.md"), "bloc $block listé dans doc/index.md");
}

echo "\n";
if ($errors) {
    echo '✗ SMOKE TEST ÉCHOUÉ : ' . count($errors) . " erreur(s), $passed vérification(s) OK\n";
    exit(1);
}
echo "✓ Smoke test OK : $passed vérifications passées\n";
exit(0);
