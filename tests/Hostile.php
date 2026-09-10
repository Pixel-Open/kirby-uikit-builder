<?php

namespace PixelOpen\KirbyUikitBuilder\Tests;

/**
 * Replays theme variants with hostile values.
 *
 * The threat model is the one from the 2026-07-07 audit: the attacker holds a
 * Panel account, so they write into the fields. Nothing stops them from
 * entering a quote, an angle bracket or a CSS declaration; the rendering has to
 * turn that into text, never into markup or executable style.
 *
 * Out of scope: the "writer" and "blocks" fields. Their content is HTML by
 * construction, exactly like kirbytext, and the plugin emits it as is on
 * purpose. Neutralising them here would test Kirby, not the plugin.
 *
 * Only fields already present in a variant are rewritten: the hostile variant
 * has to stay the same layout, not switch on another one.
 */
final class Hostile
{
    /**
     * Two payload sets, because one field can end up in two different
     * contexts. A "text" field is a label in most blocks, but a CSS length for
     * the height of a divider or a column: the payload that breaks one says
     * nothing about the other.
     *
     * "html" targets attribute and text escaping, "css" targets adding a
     * declaration inside a style attribute. Every payload in the css set
     * carries evil.test, which is what the assertions look for.
     */
    public const PAYLOADS = [
        'html' => [
            'text'     => '"><script>alert(1)</script>',
            'textarea' => '"><script>alert(1)</script>',
            'slug'     => 'ancre" onmouseover="alert(1)',
            'url'      => 'javascript:alert(1)',
            'email'    => 'victime" onmouseover="alert(1)@evil.test',
            'tel'      => '+33" onmouseover="alert(1)',
            'color'    => '#f00;background-image:url(//evil.test/x.png)',
        ],
        'css' => [
            'text'     => '150px;background-image:url(//evil.test/x.png)',
            'textarea' => '150px;background-image:url(//evil.test/x.png)',
            'slug'     => '150px;background-image:url(//evil.test/x.png)',
            'url'      => 'https://example.test/x.png);background-image:url(//evil.test/y.png',
            'email'    => 'a@example.test;background-image:url(//evil.test/x.png)',
            'tel'      => '+33;background-image:url(//evil.test/x.png)',
            'color'    => '#f00;background-image:url(//evil.test/x.png)',
        ],
    ];

    /** @return list<string> */
    public static function sets(): array
    {
        return array_keys(self::PAYLOADS);
    }

    /**
     * Identical variant, with a payload in every sensitive field.
     */
    public static function variant(array $variant, string $set = 'html'): array
    {
        if (isset($variant['block']) === true) {
            $variant['block'] = self::block($variant['block'], $set);

            return $variant;
        }

        $variant['layout']['attrs'] = self::content(
            $variant['layout']['attrs'] ?? [],
            Blueprint::layoutFields(),
            $set
        );

        foreach ($variant['layout']['columns'] ?? [] as $i => $column) {
            foreach ($column['blocks'] ?? [] as $j => $block) {
                $variant['layout']['columns'][$i]['blocks'][$j] = self::block($block, $set);
            }
        }

        return $variant;
    }

    private static function block(array $spec, string $set): array
    {
        $spec['content'] = self::content(
            $spec['content'] ?? [],
            Blueprint::blockFields($spec['type']),
            $set
        );

        return $spec;
    }

    /**
     * @param array<string, array> $fields definitions taken from the blueprint
     */
    private static function content(array $content, array $fields, string $set): array
    {
        $payloads = self::PAYLOADS[$set] ?? throw new \InvalidArgumentException("Jeu inconnu : $set");

        foreach ($fields as $name => $definition) {
            if (array_key_exists($name, $content) === false) {
                continue;
            }

            $type = $definition['type'] ?? null;

            if ($type === 'structure' && is_array($content[$name]) === true) {
                foreach ($content[$name] as $row => $values) {
                    if (is_array($values) === true) {
                        $content[$name][$row] = self::content($values, $definition['fields'] ?? [], $set);
                    }
                }
                continue;
            }

            if (isset($payloads[$type]) === true) {
                $content[$name] = $payloads[$type];
            }
        }

        return $content;
    }
}
