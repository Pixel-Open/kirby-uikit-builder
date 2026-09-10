<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Render;

use PHPUnit\Framework\Attributes\DataProvider;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Tests\Theme;

/**
 * Markup regression guard: every variant is compared to the HTML recorded in
 * tests/snapshots/.
 *
 * A snapshot never says a rendering is correct, only that it changed. Its value
 * is in the review: a pull request diff shows exactly what a refactor moved,
 * including what no assertion anticipated.
 *
 * Regenerate after an intended change:
 *
 *     composer snapshots
 *
 * then read the diff before committing it: that is the only step separating a
 * fix from a regression.
 */
final class SnapshotTest extends TestCase
{
    private const DIR = __DIR__ . '/../snapshots';

    public static function variants(): array
    {
        return Theme::allVariants();
    }

    #[DataProvider('variants')]
    public function testMarkupMatchesItsSnapshot(string $theme, array $variant): void
    {
        $file   = self::DIR . '/' . $theme . '--' . $variant['name'] . '.html';
        $actual = self::normalise(Theme::render($variant));

        if (getenv('UPDATE_SNAPSHOTS') === '1') {
            file_put_contents($file, $actual);
            $this->addToAssertionCount(1);
            return;
        }

        $this->assertFileExists(
            $file,
            "snapshot absent pour « {$variant['label']} ». Le créer : composer snapshots"
        );

        $this->assertSame(
            file_get_contents($file),
            $actual,
            "le rendu de « {$variant['label']} » a changé"
        );
    }

    public function testNoOrphanSnapshotRemains(): void
    {
        $expected = [];

        foreach (Theme::allVariants() as [$theme, $variant]) {
            $expected[$theme . '--' . $variant['name'] . '.html'] = true;
        }

        $orphans = array_diff(
            array_map('basename', glob(self::DIR . '/*.html') ?: []),
            array_keys($expected)
        );

        $this->assertSame(
            [],
            array_values($orphans),
            'snapshots sans variante correspondante, à supprimer : ' . implode(', ', $orphans)
        );
    }

    /**
     * Neutralises what changes between two runs without the markup moving:
     * media URLs carry a hash of the file and its modification date, and the
     * fixture images are regenerated on every run. The variant name is kept: it
     * encodes the requested width.
     */
    private static function normalise(string $html): string
    {
        $html = preg_replace('#/media/pages/[^/"\s]+/[0-9a-f]+-\d+/#', '/media/@/', $html);
        $html = preg_replace('#[ \t]+$#m', '', $html);

        return trim(str_replace("\r\n", "\n", $html)) . "\n";
    }
}
