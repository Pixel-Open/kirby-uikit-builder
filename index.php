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

// Blocks: every YAML in blueprints/blocks/ is registered automatically, as a
// closure so select options get translated (see $translateBlueprint).
// Slider and carousel also receive their shared tabs.
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

// Reusable fields: fields/layout is registered separately below (its options
// can be overridden from config.php).
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

// Resolves a composite link_type / link_page / link_url… value.
// The prefix lets a block carry several links: the CTA passes "btn1_" and
// "btn2_", blocks with a single link pass nothing.
// Registered on blocks and on structure rows (pricing block).
$linkHref = function (string $prefix = ''): ?string {
    $field = fn(string $name) => $this->content()->get($prefix . $name);

    // The result goes through Url::safe: escaping an href does not neutralise
    // "javascript:", which still runs on click. Scheme validation is applied to
    // the output of the match so it also covers link types added later.
    return \PixelOpen\KirbyUikitBuilder\Url::safe(match ($field('link_type')->value()) {
        'internal'  => $field('link_page')->toPage()?->url(),
        'url'       => $field('link_url')->value() ?: null,
        'anchor'    => ($anchor = $field('link_anchor')->value()) ? '#' . $anchor : null,
        'file'      => $field('link_file')->toFile()?->url(),
        'email'     => ($email = $field('link_email')->value()) ? 'mailto:' . $email : null,
        'telephone' => ($phone = $field('link_phone')->value()) ? 'tel:' . $phone : null,
        default     => null,
    });
};

// Snippets: automatic folder/name to file mapping
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
