<?php

namespace PixelOpen\KirbyUikitBuilder\Tests;

use Kirby\Cms\Block;
use Kirby\Cms\Layout;

/**
 * Loads the theme fixtures from tests/fixtures/themes/.
 *
 * A theme is a JSON file describing a demo page: a title, an intro, and a list
 * of variants. Each variant carries either a "block" (rendered by its snippet)
 * or a "layout" (rendered by layout/section).
 *
 * Those same files feed the render tests and the demo page generator: an option
 * not described here is neither tested nor shown.
 *
 * Content conventions:
 *
 *   - a "files" field holds a file name from the fixture page ("large.jpg"),
 *     not a uuid;
 *   - a nested "blocks" field holds an array of { type, content } objects,
 *     which the loader serialises to JSON the way the Panel would;
 *   - a "structure" field holds an array of objects with no "type" key.
 */
final class Theme
{
    public const DIR = __DIR__ . '/fixtures/themes';

    /**
     * Names of the available themes, in alphabetical order.
     *
     * @return list<string>
     */
    public static function names(): array
    {
        return array_map(
            fn (string $file) => basename($file, '.json'),
            glob(self::DIR . '/*.json') ?: []
        );
    }

    /**
     * @return array{slug: string, title: string, intro: string, display: string, variants: list<array>}
     */
    public static function load(string $name): array
    {
        $file = self::DIR . '/' . $name . '.json';

        if (is_file($file) === false) {
            throw new \InvalidArgumentException("Thème inconnu : $name");
        }

        try {
            $data = json_decode(file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            // The native message does not say which file is at fault, which
            // makes the error unreadable when it surfaces from a data provider.
            throw new \JsonException("$name.json : " . $e->getMessage(), previous: $e);
        }

        foreach ($data['variants'] ?? [] as $index => $variant) {
            foreach (['name', 'label'] as $key) {
                if (empty($variant[$key]) === true) {
                    throw new \UnexpectedValueException("$name : variante #$index sans « $key »");
                }
            }
            if (isset($variant['block']) === false && isset($variant['layout']) === false) {
                throw new \UnexpectedValueException(
                    "$name : la variante « {$variant['name']} » n'a ni « block » ni « layout »"
                );
            }
        }

        // display: "banner" presents each variant under a heading strip, which
        // suits a catalogue. "flow" chains the sections with nothing in
        // between, the only way to show what happens between two sections,
        // such as a shape divider.
        // slug: the demo page URL, which stays in the site's language while the
        // fixture file is named in English. It defaults to the file name, so
        // only a theme whose two names differ has to declare it.
        return $data + [
            'slug'     => $name,
            'title'    => $name,
            'intro'    => '',
            'display'  => 'banner',
            'variants' => [],
        ];
    }

    /**
     * Every variant of every theme, flattened for a data provider.
     *
     * @return array<string, array{0: string, 1: array}> keyed "theme/variant"
     */
    public static function allVariants(): array
    {
        $out = [];

        foreach (self::names() as $theme) {
            foreach (self::load($theme)['variants'] as $variant) {
                $out["$theme/{$variant['name']}"] = [$theme, $variant];
            }
        }

        return $out;
    }

    /**
     * A named variant, for tests targeting one specific rendering.
     */
    public static function variant(string $theme, string $name): array
    {
        foreach (self::load($theme)['variants'] as $variant) {
            if ($variant['name'] === $name) {
                return $variant;
            }
        }

        throw new \InvalidArgumentException("Variante inconnue : $theme/$name");
    }

    /**
     * Block types referenced across all themes, including those nested in a
     * "blocks" field.
     *
     * @return list<string>
     */
    public static function coveredBlockTypes(): array
    {
        $types = [];

        // A block can hold others (card, tabs, media-object, slides…): the
        // search walks the whole content, at any depth.
        $collect = function (array $blocks) use (&$collect, &$types): void {
            foreach ($blocks as $block) {
                if (isset($block['type']) === false) {
                    continue;
                }

                $types[$block['type']] = true;

                $descend = function (mixed $value) use (&$descend, &$collect): void {
                    if (is_array($value) === false) {
                        return;
                    }
                    if (self::isBlockList($value) === true) {
                        $collect($value);
                        return;
                    }
                    foreach ($value as $child) {
                        $descend($child);
                    }
                };

                $descend($block['content'] ?? []);
            }
        };

        foreach (self::names() as $theme) {
            foreach (self::load($theme)['variants'] as $variant) {
                if (isset($variant['block']) === true) {
                    $collect([$variant['block']]);
                }
                foreach ($variant['layout']['columns'] ?? [] as $column) {
                    $collect($column['blocks'] ?? []);
                }
            }
        }

        ksort($types);

        return array_keys($types);
    }

    /**
     * Renders a variant the way it will appear on the demo page.
     */
    public static function render(array $variant): string
    {
        if (isset($variant['block']) === true) {
            return self::block($variant['block'], $variant['name'])->toHtml();
        }

        return snippet('layout/section', ['layout' => self::layout($variant)], true);
    }

    public static function block(array $spec, string $id): Block
    {
        return new Block([
            'type'    => $spec['type'],
            'content' => self::content($spec['content'] ?? []),
            'id'      => $id . '-' . $spec['type'],
            'parent'  => Fixture::page(),
        ]);
    }

    public static function layout(array $variant): Layout
    {
        $columns = [];

        foreach ($variant['layout']['columns'] ?? [] as $index => $column) {
            $columns[] = [
                'id'     => $variant['name'] . '-col-' . $index,
                'width'  => $column['width'] ?? '1/1',
                'blocks' => array_map(
                    fn (array $block, int $n) => [
                        'type'    => $block['type'],
                        'content' => self::content($block['content'] ?? []),
                        'id'      => $variant['name'] . "-$index-$n-" . $block['type'],
                    ],
                    $column['blocks'] ?? [],
                    array_keys($column['blocks'] ?? [])
                ),
            ];
        }

        return new Layout([
            'id'      => $variant['name'],
            'attrs'   => $variant['layout']['attrs'] ?? [],
            'columns' => $columns,
            'parent'  => Fixture::page(),
        ]);
    }

    /**
     * Serialises nested "blocks" fields, which Kirby stores as JSON.
     *
     * An array of arrays carrying a "type" key is a block list; structures
     * never carry one. That criterion is therefore enough to tell them apart
     * without listing field names one by one.
     */
    private static function content(array $content): array
    {
        foreach ($content as $key => $value) {
            if (self::isBlockList($value) === true) {
                $content[$key] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                continue;
            }

            if (is_array($value) === true) {
                foreach ($value as $row => $fields) {
                    if (is_array($fields) === true) {
                        $content[$key][$row] = self::content($fields);
                    }
                }
            }
        }

        return $content;
    }

    private static function isBlockList(mixed $value): bool
    {
        if (is_array($value) === false || $value === [] || array_is_list($value) === false) {
            return false;
        }

        foreach ($value as $entry) {
            if (is_array($entry) === false || isset($entry['type']) === false) {
                return false;
            }
        }

        return true;
    }
}
