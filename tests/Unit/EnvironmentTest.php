<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use Kirby\Cms\Block;
use Kirby\Cms\Layout;
use PixelOpen\KirbyUikitBuilder\Section;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;

/**
 * Validates the test foundation itself: without these four points, any failure
 * in the later suites would be ambiguous (a plugin bug, or a miswired
 * fixture?).
 */
final class EnvironmentTest extends TestCase
{
    public function testPluginIsRegistered(): void
    {
        $this->assertNotNull($this->kirby->plugin('pixelopen/kirby-uikit-builder'));
    }

    public function testBlueprintsResolveAndAreTranslated(): void
    {
        $blueprints = $this->kirby->extensions('blueprints');

        $this->assertArrayHasKey('blocks/slider', $blueprints);
        $this->assertArrayHasKey('fields/layout', $blueprints);

        $slider = ($blueprints['blocks/slider'])();

        $this->assertIsArray($slider);
        $this->assertNotSame([], $slider);
        $this->assertStringStartsNotWith(
            'pixelopen.kirby-uikit-builder.',
            $slider['name'] ?? '',
            'le blueprint est enregistré mais $translateBlueprint ne s\'applique pas'
        );
    }

    public function testSnippetsRender(): void
    {
        $block = Block::factory(['type' => 'spacer', 'content' => ['height' => 80]]);
        $html  = snippet('blocks/spacer', ['block' => $block], true);

        $this->assertStringContainsString('height:80px', $html);
    }

    public function testSectionPrepareRunsOnABareLayout(): void
    {
        $prepared = Section::prepare(new Layout());

        $this->assertArrayHasKey('classes', $prepared);
        $this->assertStringContainsString('uk-section', $prepared['classes']);
        $this->assertNull($prepared['sectionStyle']);
    }
}
