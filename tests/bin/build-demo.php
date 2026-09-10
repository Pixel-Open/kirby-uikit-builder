<?php

/**
 * Generates the demo pages from the theme fixtures.
 *
 * One page per theme, one section per variant, plus an index page. The content
 * produced is versioned: generation therefore has to be deterministic down to
 * the bit, otherwise every run would produce a diff of noise. Hence UUIDs
 * derived from the file name rather than drawn at random, a fixed key order and
 * no dates.
 *
 * Media files in the output folder are never touched: they are the real photos,
 * uploaded by hand. Only the .txt files are rewritten, and stale page folders
 * removed.
 *
 * Usage: php tests/bin/build-demo.php [--out <content folder>]
 */

use PixelOpen\KirbyUikitBuilder\Tests\Theme;

$autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';

if (is_file($autoload) === false) {
    fwrite(STDERR, "Dépendances absentes. Lancer d'abord : composer install\n");
    exit(1);
}

require $autoload;

// --- Options ---------------------------------------------------------------

$options = getopt('', ['out:', 'quiet']);
$out     = rtrim($options['out'] ?? dirname(__DIR__, 3) . '/data/storage/content/9_demo', '/');
$quiet   = isset($options['quiet']);
$say     = fn (string $line) => $quiet ? null : print($line . "\n");

if (is_dir($out) === false) {
    fwrite(STDERR, "Dossier de sortie introuvable : $out\n");
    exit(1);
}

// --- Page order ------------------------------------------------------------

// The demo order, from the most illustrative to the most technical. A theme
// missing from this list is appended at the end, in alphabetical order:
// forgetting to declare it therefore makes nothing disappear.
$order = [
    'slider', 'carousel', 'cta', 'card', 'icon-box', 'stats', 'testimonials',
    'team', 'pricing', 'logos', 'image', 'gallery', 'video', 'media-object',
    'faq', 'tabs', 'alerts', 'table', 'timeline', 'content',
    'layouts', 'dividers', 'animations',
];

$themes = Theme::names();
$sorted = array_values(array_filter($order, fn (string $t) => in_array($t, $themes, true)));
$sorted = array_merge($sorted, array_values(array_diff($themes, $sorted)));

/**
 * Serialises a field array into Kirby's text format.
 */
$content = function (array $fields): string {
    $parts = [];

    foreach ($fields as $key => $value) {
        $parts[] = ucfirst($key) . ': ' . $value;
    }

    return implode("\n\n----\n\n", $parts) . "\n";
};

// --- Media files -----------------------------------------------------------

/**
 * Stable UUID derived from the file name: two runs produce the same one, and
 * child pages can reference it without reading the disk.
 */
$uuid = fn (string $key) => 'demo' . substr(sha1('kirby-uikit-builder/demo/' . $key), 0, 12);

$media = [];
foreach (scandir($out) ?: [] as $entry) {
    if ($entry[0] === '.' || is_dir($out . '/' . $entry) || str_ends_with($entry, '.txt')) {
        continue;
    }
    $media[$entry] = $uuid($entry);
}

if ($media === []) {
    fwrite(STDERR, "Aucun fichier média dans $out : les pages seraient sans images.\n");
    exit(1);
}

// Descriptions of the visuals, written from the files themselves. They feed
// the alt attribute, the lightbox captions and the galleries' "caption on
// hover" option, which without them would show nothing.
$descriptions = [
    'slider-1.jpg'    => [
        'Un arbre isolé penché au-dessus d\'un lac de montagne, au crépuscule',
        'Crépuscule violet sur le lac',
    ],
    'slider-2.jpg'    => [
        'Un lac alpin turquoise, un îlot rocheux planté de sapins, des montagnes boisées aux couleurs d\'automne',
        'Îlot rocheux au petit matin',
    ],
    'slider-3.jpg'    => [
        'Un versant de montagne couvert de rhododendrons roses en fleurs, au lever du soleil',
        'Rhododendrons en fleurs au lever du soleil',
    ],
    'small-image.jpg' => [
        'Un intérieur contemporain : verrière noire, escalier en bois suspendu, chaises jaunes',
        'Verrière et escalier suspendu',
    ],
    'team-1.jpg'      => ['Portrait d\'une personne souriante, bras croisés, chemise bleue à pois', ''],
    'team-2.jpg'      => ['Portrait d\'une personne souriante de profil, en extérieur, t-shirt ocre', ''],
    'team-3.jpg'      => ['Portrait d\'une personne souriante, chemise à petits carreaux, fond sombre', ''],
    'vertical-1.jpg'  => [
        'Un demi-pamplemousse, un demi-citron vert et un quartier d\'orange sur fond orange',
        'Agrumes coupés',
    ],
    'vertical-2.jpg'  => [
        'Un papillon azuré posé sur une fleur de chardon, sur un fond vert flouté',
        'Azuré sur un chardon',
    ],
    'vertical-3.jpg'  => [
        'Une truffe au chocolat dans une coupelle et un verre de thé sur une planche de marbre',
        'Thé et truffe au chocolat',
    ],
];

foreach ($media as $filename => $id) {
    $fields = ['uuid' => $id];

    if (isset($descriptions[$filename]) === true) {
        [$alt, $caption] = $descriptions[$filename];
        $fields['alt'] = $alt;
        if ($caption !== '') {
            $fields['caption'] = $caption;
        }
    }

    file_put_contents($out . '/' . $filename . '.txt', $content($fields));
}
$say(count($media) . ' fichiers médias, UUID et descriptions écrits');

// --- Content writing -------------------------------------------------------

/**
 * Replaces the file names in "files" fields with their file://uuid.
 * A name with no matching media is left as is: the variant will render empty
 * rather than failing the whole generation, and the message says so.
 */
$manquants = [];
$resolve = function (mixed $value) use (&$resolve, $media, &$manquants): mixed {
    if (is_array($value) === true) {
        return array_map($resolve, $value);
    }

    if (is_string($value) === true && isset($media[$value]) === true) {
        return 'file://' . $media[$value];
    }

    if (is_string($value) === true && preg_match('/^[\w-]+\.(jpg|jpeg|png|svg|gif|webp|mp4|webm)$/i', $value) === 1) {
        $manquants[$value] = true;
    }

    return $value;
};

$blocks = fn (array $list, string $prefix) => array_map(
    fn (array $block, int $n) => [
        'content'  => $resolve($block['content'] ?? []),
        'id'       => $prefix . '-' . $n . '-' . $block['type'],
        'isHidden' => false,
        'type'     => $block['type'],
    ],
    $list,
    array_keys($list)
);

$pages = [];

foreach ($sorted as $index => $theme) {
    $data    = Theme::load($theme);
    $layouts = [];

    foreach ($data['variants'] as $variant) {
        // The label and the note travel in the row attributes: Section::prepare
        // ignores what it does not know, and the metadata stays tied to its
        // variant without a parallel structure to keep in sync.
        $attrs = ($variant['layout']['attrs'] ?? []) + [
            'demo_label' => $variant['label'],
            'demo_note'  => $variant['note'] ?? '',
        ];

        $columns = [];

        if (isset($variant['block']) === true) {
            $columns[] = [
                'blocks' => $blocks([$variant['block']], $variant['name'] . '-0'),
                'id'     => $variant['name'] . '-col-0',
                'width'  => '1/1',
            ];
        }

        foreach ($variant['layout']['columns'] ?? [] as $n => $column) {
            $columns[] = [
                'blocks' => $blocks($column['blocks'] ?? [], $variant['name'] . '-' . $n),
                'id'     => $variant['name'] . '-col-' . $n,
                'width'  => $column['width'] ?? '1/1',
            ];
        }

        $layouts[] = [
            'attrs'   => $resolve($attrs),
            'columns' => $columns,
            'id'      => $variant['name'],
        ];
    }

    // The folder name, and therefore the page URL, comes from the slug rather
    // than the file name: the fixtures are named in English, the demo pages
    // keep the URLs the site was published with.
    $slug = $data['slug'];
    $dir  = $out . '/' . ($index + 1) . '_' . $slug;

    if (is_dir($dir) === false) {
        mkdir($dir, 0777, true);
    }

    file_put_contents($dir . '/demo.txt', $content([
        'title'   => $data['title'],
        'intro'   => $data['intro'],
        'display' => $data['display'],
        'layout'  => json_encode($layouts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'uuid'    => $uuid('page/' . $slug),
    ]));

    $pages[] = [$slug, $data['title'], count($data['variants'])];
    $say(sprintf('  %-16s %2d variantes', $slug, count($data['variants'])));
}

file_put_contents($out . '/demo-index.txt', $content([
    'title' => 'Démonstration',
    'intro' => 'Toutes les possibilités du plugin kirby-uikit-builder, une page par sujet. '
             . 'Ces pages sont générées depuis les fixtures de test : make demo.',
    'uuid'  => $uuid('page/index'),
]));

// --- Cleanup ---------------------------------------------------------------

$attendus = array_map(fn (int $i, array $p) => ($i + 1) . '_' . $p[0], array_keys($pages), $pages);

foreach (scandir($out) ?: [] as $entry) {
    if ($entry[0] === '.' || is_dir($out . '/' . $entry) === false || in_array($entry, $attendus, true)) {
        continue;
    }

    array_map('unlink', glob($out . '/' . $entry . '/*') ?: []);
    rmdir($out . '/' . $entry);
    $say("  page obsolète supprimée : $entry");
}

if ($manquants !== []) {
    fwrite(STDERR, "\nFichiers absents de $out, variantes concernées incomplètes :\n  "
        . implode("\n  ", array_keys($manquants)) . "\n");
}

$say("\n" . count($pages) . ' pages écrites dans ' . $out);
