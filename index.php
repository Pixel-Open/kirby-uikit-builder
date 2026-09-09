<?php

use Kirby\Data\Data;

$translateBlueprint = function (array $data): array {
    array_walk_recursive($data, function (&$value) {
        if (is_string($value) && str_starts_with($value, 'pixelopen.kirby-uikit-builder.')) {
            $value = t($value, $value);
        }
    });
    return $data;
};

$sharedTab = fn(string $name) => Data::read(__DIR__ . '/blueprints/tabs/' . $name . '.yml');

$fieldGroup = fn(string $name) => require __DIR__ . '/blueprints/fields/group-' . $name . '.php';


$behaviorTab = [
    'label'  => 'pixelopen.kirby-uikit-builder.tab.behavior',
    'fields' => array_merge(
        $fieldGroup('autoplay'),
        $fieldGroup('playback'),
    ),
];

// Blocs : chaque YAML de blueprints/blocks/ est enregistré automatiquement,
// en closure pour la traduction des options de select (voir $translateBlueprint).
// Slider et carousel reçoivent en plus leurs tabs partagés.
$blueprints = [];
foreach (glob(__DIR__ . '/blueprints/blocks/*.yml') as $file) {
    $name = 'blocks/' . basename($file, '.yml');
    $blueprints[$name] = function () use ($translateBlueprint, $sharedTab, $behaviorTab, $file, $name) {
        $blueprint = Data::read($file);
        if ($name === 'blocks/slider') {
            $blueprint['tabs']['content'] = $sharedTab('content');
        }
        if (in_array($name, ['blocks/slider', 'blocks/carousel'])) {
            $blueprint['tabs']['behavior'] = $behaviorTab;
        }
        return $translateBlueprint($blueprint);
    };
}

// Champs réutilisables : fields/layout est enregistré à part plus bas
// (surcharge des options via config.php).
foreach (glob(__DIR__ . '/blueprints/fields/*.yml') as $file) {
    $name = 'fields/' . basename($file, '.yml');
    if ($name === 'fields/layout') {
        continue;
    }
    $blueprints[$name] = function () use ($translateBlueprint, $file) {
        return $translateBlueprint(Data::read($file));
    };
}

$blueprints['fields/layout'] = function () use ($translateBlueprint) {
    $blueprint = Data::read(__DIR__ . '/blueprints/fields/layout.yml');

    $layouts = option('pixelopen.kirby-uikit-builder.layouts');
    if ($layouts !== null) {
        $blueprint['layouts'] = $layouts;
    }

    $backgrounds = option('pixelopen.kirby-uikit-builder.backgrounds');
    if ($backgrounds !== null) {
        $blueprint['settings']['tabs']['fond']['fields']['background']['options'] = $backgrounds;
    }

    $paddings = option('pixelopen.kirby-uikit-builder.paddings');
    if ($paddings !== null) {
        $blueprint['settings']['tabs']['mise_en_page']['fields']['padding']['options'] = $paddings;
    }

    $containers = option('pixelopen.kirby-uikit-builder.containers');
    if ($containers !== null) {
        $blueprint['settings']['tabs']['mise_en_page']['fields']['container']['options'] = $containers;
    }

    $visibilities = option('pixelopen.kirby-uikit-builder.visibilities');
    if ($visibilities !== null) {
        $blueprint['settings']['tabs']['avance']['fields']['visibility']['options'] = $visibilities;
    }

    $scrollspyAnimations = option('pixelopen.kirby-uikit-builder.scrollspy-animations');
    if ($scrollspyAnimations !== null) {
        $blueprint['settings']['tabs']['effets']['fields']['scrollspy_cls']['options'] = $scrollspyAnimations;
    }

    $extraClasses = option('pixelopen.kirby-uikit-builder.css-classes', []);
    if ($extraClasses) {
        $existing = $blueprint['settings']['tabs']['avance']['fields']['css_classes']['options'] ?? [];
        $blueprint['settings']['tabs']['avance']['fields']['css_classes']['options'] = array_merge($existing, $extraClasses);
    }

    foreach (option('pixelopen.kirby-uikit-builder.fieldsets', []) as $key => $value) {
        if (isset($blueprint['fieldsets'][$key])) {
            $blueprint['fieldsets'][$key]['fieldsets'] = array_merge(
                $blueprint['fieldsets'][$key]['fieldsets'] ?? [],
                (array)$value
            );
        } else {
            $blueprint['fieldsets'][$key] = $value;
        }
    }

    return $translateBlueprint($blueprint);
};

// Résolution d'un lien composite link_type / link_page / link_url…
// Le préfixe permet à un bloc de porter plusieurs liens : le CTA passe
// "btn1_" et "btn2_", les blocs à lien unique ne passent rien.
// Enregistré sur les blocs et sur les lignes de structure (bloc pricing).
$linkHref = function (string $prefix = ''): ?string {
    $field = fn(string $name) => $this->content()->get($prefix . $name);

    return match ($field('link_type')->value()) {
        'internal'  => $field('link_page')->toPage()?->url(),
        'url'       => $field('link_url')->value() ?: null,
        'anchor'    => ($anchor = $field('link_anchor')->value()) ? '#' . $anchor : null,
        'file'      => $field('link_file')->toFile()?->url(),
        'email'     => ($email = $field('link_email')->value()) ? 'mailto:' . $email : null,
        'telephone' => ($phone = $field('link_phone')->value()) ? 'tel:' . $phone : null,
        default     => null,
    };
};

// Snippets : mapping automatique dossier/nom → fichier
$snippets = [];
foreach (['blocks', 'ui', 'layout'] as $dir) {
    foreach (glob(__DIR__ . '/snippets/' . $dir . '/*.php') as $file) {
        $snippets[$dir . '/' . basename($file, '.php')] = $file;
    }
}

Kirby::plugin('pixelopen/kirby-uikit-builder', [
    'translations' => [
        'fr' => require __DIR__ . '/translations/fr.php',
        'en' => require __DIR__ . '/translations/en.php',
    ],
    'blueprints'   => $blueprints,
    'snippets'     => $snippets,
    'blockMethods' => [
        'linkHref' => $linkHref,
    ],
    'structureObjectMethods' => [
        'linkHref' => $linkHref,
    ],
]);
