<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use PixelOpen\KirbyUikitBuilder\Tests\Blueprint;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Tests\Theme;

/**
 * Checks every fixture key against the blueprint of the block it configures.
 *
 * Renaming a field in a YAML breaks nothing visible: Kirby ignores a content
 * key no field claims, the variant keeps rendering, and the setting simply
 * stops having an effect. The fixtures then become misleading, and the demo
 * pages show an option that no longer does anything. This test is the only
 * place where that gap becomes visible.
 */
final class FixtureSchemaTest extends TestCase
{
    /**
     * Keys the demo generator adds to the row attributes, deliberately absent
     * from the blueprint: they carry display metadata, which Section::prepare
     * ignores.
     */
    private const GENERATED_ATTRS = ['demo_label', 'demo_note'];

    public function testEveryBlockKeyExistsInItsBlueprint(): void
    {
        $unknown = [];

        foreach (Theme::names() as $theme) {
            foreach (Theme::load($theme)['variants'] as $variant) {
                foreach (self::blocksOf($variant) as $block) {
                    $this->collectUnknown(
                        $block['content'] ?? [],
                        Blueprint::blockFields($block['type']),
                        "$theme/{$variant['name']} → bloc {$block['type']}",
                        $unknown
                    );
                }
            }
        }

        $this->assertSame([], $unknown, "clés inconnues du blueprint :\n  " . implode("\n  ", $unknown));
    }

    public function testEveryLayoutAttributeExistsInTheLayoutBlueprint(): void
    {
        $fields  = Blueprint::layoutFields();
        $unknown = [];

        foreach (Theme::names() as $theme) {
            foreach (Theme::load($theme)['variants'] as $variant) {
                foreach ($variant['layout']['attrs'] ?? [] as $key => $value) {
                    if (isset($fields[$key]) === false && in_array($key, self::GENERATED_ATTRS, true) === false) {
                        $unknown[] = "$theme/{$variant['name']} → attribut « $key »";
                    }
                }
            }
        }

        $this->assertSame([], $unknown, "attributs inconnus de fields/layout :\n  " . implode("\n  ", $unknown));
    }

    /**
     * Every block in a variant, including those nested in a "blocks" field
     * (card, tabs, media-object, slider slides…).
     *
     * @return list<array{type: string, content: array}>
     */
    private static function blocksOf(array $variant): array
    {
        $found = [];

        $walk = function (array $blocks) use (&$walk, &$found): void {
            foreach ($blocks as $block) {
                if (isset($block['type']) === false) {
                    continue;
                }

                $found[] = $block;

                $descend = function (mixed $value) use (&$descend, &$walk): void {
                    if (is_array($value) === false) {
                        return;
                    }
                    if (self::isBlockList($value) === true) {
                        $walk($value);
                        return;
                    }
                    foreach ($value as $child) {
                        $descend($child);
                    }
                };

                $descend($block['content'] ?? []);
            }
        };

        if (isset($variant['block']) === true) {
            $walk([$variant['block']]);
        }

        foreach ($variant['layout']['columns'] ?? [] as $column) {
            $walk($column['blocks'] ?? []);
        }

        return $found;
    }

    /**
     * @param list<string> $unknown collected by reference
     */
    private function collectUnknown(array $content, array $fields, string $where, array &$unknown): void
    {
        foreach ($content as $key => $value) {
            $definition = $fields[$key] ?? null;

            if ($definition === null) {
                $unknown[] = "$where → « $key »";
                continue;
            }

            // Structure rows are checked against the structure's fields, not
            // against the block's.
            if (($definition['type'] ?? null) === 'structure' && is_array($value) === true) {
                foreach ($value as $row) {
                    if (is_array($row) === true) {
                        $this->collectUnknown($row, $definition['fields'] ?? [], "$where → {$key}[]", $unknown);
                    }
                }
            }
        }
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
