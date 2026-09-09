<?php

/**
 * Smoke test autonome du plugin pixelopen/kirby-uikit-builder.
 *
 * Contrairement au smoke test du dépôt vitrine, celui-ci ne suppose aucun site
 * autour du plugin : il installe une arborescence Kirby minimale dans un dossier
 * temporaire, y lie le plugin comme le ferait site/plugins/, et boote Kirby.
 *
 * Vérifie :
 *   1. que le plugin est enregistré ;
 *   2. que chaque blueprint de bloc/champ se résout en tableau non vide ;
 *   3. la cohérence blueprint / snippet pour les blocs ;
 *   4. qu'aucune clé de traduction pixelopen.kirby-uikit-builder.* ne reste non résolue ;
 *   5. la parité des clés entre translations/fr.php et translations/en.php ;
 *   6. que les classes PSR-4 du plugin sont autoloadables ;
 *   7. que chaque bloc a une icône existant dans le sprite du Panel ;
 *   8. que chaque bloc est proposé dans les fieldsets de fields/layout.yml ;
 *   9. que chaque bloc a sa page de doc et sa ligne dans doc/index.md.
 *
 * Usage : composer install && php tests/smoke.php   (exit 0 = OK, exit 1 = échec)
 */

use Kirby\Cms\App as Kirby;
use Kirby\Data\Data;

$plugin   = dirname(__DIR__);
$autoload = $plugin . '/vendor/autoload.php';

if (is_file($autoload) === false) {
    fwrite(STDERR, "Dépendances absentes. Lancer d'abord : composer install\n");
    exit(1);
}

require $autoload;

// Arborescence Kirby minimale : le plugin est lié comme dans site/plugins/.
$fixture = sys_get_temp_dir() . '/kirby-uikit-builder-smoke-' . getmypid();
$plugins = $fixture . '/site/plugins';

register_shutdown_function(static function () use ($fixture, $plugins): void {
    @unlink($plugins . '/kirby-uikit-builder');
    foreach (['/site/plugins', '/site', '/content', '/public', ''] as $sub) {
        @rmdir($fixture . $sub);
    }
});

foreach ([$plugins, $fixture . '/content', $fixture . '/public'] as $dir) {
    if (is_dir($dir) === false && mkdir($dir, 0777, true) === false) {
        fwrite(STDERR, "Impossible de créer $dir\n");
        exit(1);
    }
}

if (symlink($plugin, $plugins . '/kirby-uikit-builder') === false) {
    fwrite(STDERR, "Impossible de lier le plugin dans la fixture\n");
    exit(1);
}

$kirby = new Kirby([
    'roots' => [
        'index'   => $fixture . '/public',
        'base'    => $fixture,
        'site'    => $fixture . '/site',
        'content' => $fixture . '/content',
    ]
]);

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

// 1. Plugin enregistré
$check($kirby->plugin('pixelopen/kirby-uikit-builder') !== null, 'plugin pixelopen/kirby-uikit-builder enregistré');

// 2. Résolution de tous les blueprints du plugin
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

    // 4. Toute chaîne encore préfixée pixelopen.kirby-uikit-builder.* après résolution
    //    est une clé de traduction manquante (t() retourne la clé en fallback).
    if (is_array($resolved)) {
        array_walk_recursive($resolved, function ($value) use (&$unresolved, $name) {
            if (is_string($value) && str_starts_with($value, 'pixelopen.kirby-uikit-builder.')) {
                $unresolved["$name : $value"] = true;
            }
        });
    }
}

$check($unresolved === [], 'aucune clé de traduction non résolue' . ($unresolved ? ' (' . implode(', ', array_keys($unresolved)) . ')' : ''));

// 3. Cohérence blueprint / snippet pour les blocs
$snippets = $kirby->extensions('snippets');
foreach (glob($plugin . '/blueprints/blocks/*.yml') as $file) {
    $block = basename($file, '.yml');
    $check(isset($snippets['blocks/' . $block]), "snippet blocks/$block présent pour son blueprint");
}
foreach (glob($plugin . '/snippets/blocks/*.php') as $file) {
    $block = basename($file, '.php');
    $check(isset($extensions['blocks/' . $block]), "blueprint blocks/$block présent pour son snippet");
}

// 5. Parité des clés de traduction fr / en
$fr = require $plugin . '/translations/fr.php';
$en = require $plugin . '/translations/en.php';
$missingEn = array_keys(array_diff_key($fr, $en));
$missingFr = array_keys(array_diff_key($en, $fr));
$check($missingEn === [], 'clés fr toutes présentes en en' . ($missingEn ? ' (manquantes : ' . implode(', ', $missingEn) . ')' : ''));
$check($missingFr === [], 'clés en toutes présentes en fr' . ($missingFr ? ' (manquantes : ' . implode(', ', $missingFr) . ')' : ''));

// 6. Classes PSR-4 du plugin
foreach (glob($plugin . '/src/*.php') as $file) {
    $class = 'PixelOpen\\KirbyUikitBuilder\\' . basename($file, '.php');
    $check(class_exists($class), "classe $class autoloadable");
}

// 7. Icônes des blocs : définies et présentes dans le sprite du Panel
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

// 8. Chaque bloc est proposé dans les fieldsets de fields/layout.yml.
//    Un bloc absent de cette liste est valide mais invisible dans le Panel.
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
        continue; // fieldset de layout, pas un bloc de contenu
    }
    $check(isset($offered[$block]), "bloc $block proposé dans les fieldsets de fields/layout.yml");
}

// 9. Couverture documentaire
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
