<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Tests\Theme;

/**
 * The "dividers" theme reads as a flow: a shape divider there is not an
 * ornament laid on a section, it is the transition to the next one. Its color
 * therefore has to be that of the background coming after it, otherwise the
 * shape reads as a stuck-on band instead of blending in.
 *
 * Nothing in the rendering flags a mistake here: the page stays valid, it only
 * becomes wrong to the eye. Hence this test on the fixtures themselves.
 */
final class DividerChainTest extends TestCase
{
    /** Actual backgrounds of the UIkit section classes, theme defaults. */
    private const BACKGROUNDS = [
        ''                     => '#ffffff',
        'uk-section-default'   => '#ffffff',
        'uk-section-muted'     => '#f8f8f8',
        'uk-section-primary'   => '#1e87f0',
        'uk-section-secondary' => '#222222',
    ];

    /**
     * The template closes the page with a "generated HTML" strip on a muted
     * background: that is what the last divider meets.
     */
    private const CLOSING_BACKGROUND = '#f8f8f8';

    public function testThePageIsRenderedAsAContinuousFlow(): void
    {
        $this->assertSame('flow', Theme::load('dividers')['display']);
    }

    public function testEveryDividerTakesTheColourOfTheSectionBelowIt(): void
    {
        $variants = Theme::load('dividers')['variants'];

        foreach ($variants as $index => $variant) {
            $attrs = $variant['layout']['attrs'];

            if (($attrs['shape_divider'] ?? false) !== true) {
                continue;
            }

            if (in_array($attrs['shape_divider_position'] ?? 'bottom', ['bottom', 'both'], true) === false) {
                continue;
            }

            $next = $variants[$index + 1] ?? null;
            $below = $next === null
                ? self::CLOSING_BACKGROUND
                : self::BACKGROUNDS[$next['layout']['attrs']['background'] ?? ''];

            $this->assertSame(
                strtolower($below),
                strtolower($attrs['shape_divider_color']),
                "« {$variant['label']} » : le séparateur du bas doit reprendre le fond de la section suivante"
            );
        }
    }

    public function testTopDividersTakeTheColourOfTheSectionAboveThem(): void
    {
        $variants = Theme::load('dividers')['variants'];
        $seen     = 0;

        foreach ($variants as $index => $variant) {
            $attrs = $variant['layout']['attrs'];

            if (in_array($attrs['shape_divider_position'] ?? 'bottom', ['top', 'both'], true) === false) {
                continue;
            }

            $seen++;
            $previous = $variants[$index - 1] ?? null;
            $above = $previous === null
                ? '#ffffff'
                : self::BACKGROUNDS[$previous['layout']['attrs']['background'] ?? ''];

            // A "both" divider has a single color for both edges: the
            // sections around it therefore have to share a background.
            $this->assertSame(
                strtolower($above),
                strtolower($attrs['shape_divider_color']),
                "« {$variant['label']} » : le séparateur du haut doit reprendre le fond de la section précédente"
            );
        }

        $this->assertGreaterThan(0, $seen, 'aucune variante ne montre un séparateur en haut');
    }

    public function testEveryDividerShapeIsShown(): void
    {
        $offered = array_map(
            fn (array $option) => $option['value'],
            \Kirby\Data\Data::read(dirname(__DIR__, 2) . '/blueprints/fields/layout.yml')
                ['settings']['tabs']['mise_en_page']['fields']['shape_divider_type']['options']
        );

        $shown = array_column(
            array_column(array_column(Theme::load('dividers')['variants'], 'layout'), 'attrs'),
            'shape_divider_type'
        );

        $missing = array_diff($offered, $shown);

        $this->assertSame(
            [],
            array_values($missing),
            'formes proposées dans le Panel mais absentes de la démo : ' . implode(', ', $missing)
        );
    }
}
