<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Render;

use PHPUnit\Framework\Attributes\DataProvider;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Tests\Theme;

/**
 * Runs every variant described in tests/fixtures/themes/.
 *
 * None of these checks judges how the rendering looks: they guarantee that
 * every combination of options produces valid, complete HTML with no forgotten
 * translation key. Targeted markup checks live in BlockMarkupTest, regression
 * checks in SnapshotTest.
 */
final class BlockRenderTest extends TestCase
{
    /**
     * The safety net of the whole setup: a block added to the plugin but to no
     * theme would be neither tested nor shown on the demo pages.
     */
    public function testEveryBlockTypeIsCoveredByATheme(): void
    {
        $blocks = array_map(
            fn (string $file) => basename($file, '.yml'),
            glob(dirname(__DIR__, 2) . '/blueprints/blocks/*.yml') ?: []
        );

        $missing = array_diff($blocks, Theme::coveredBlockTypes());

        $this->assertSame(
            [],
            array_values($missing),
            'blocs sans variante dans tests/fixtures/themes/ : ' . implode(', ', $missing)
        );
    }

    public function testEveryThemeDeclaresATitleAndAnIntro(): void
    {
        foreach (Theme::names() as $name) {
            $theme = Theme::load($name);

            $this->assertNotSame('', trim($theme['title']), "thème $name sans titre");
            $this->assertNotSame('', trim($theme['intro']), "thème $name sans intro");
            $this->assertNotSame([], $theme['variants'], "thème $name sans variante");
        }
    }

    public static function variants(): array
    {
        return Theme::allVariants();
    }

    #[DataProvider('variants')]
    public function testVariantRendersNonEmptyHtml(string $theme, array $variant): void
    {
        $html = Theme::render($variant);

        $this->assertNotSame('', trim($html), "« {$variant['label']} » ne rend rien");
    }

    #[DataProvider('variants')]
    public function testVariantProducesParsableMarkup(string $theme, array $variant): void
    {
        $html = trim(Theme::render($variant));

        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $document->loadHTML(
            '<!doctype html><html lang="fr"><head><meta charset="utf-8"></head><body>' . $html . '</body></html>',
            LIBXML_NOERROR | LIBXML_NOWARNING
        );

        // Only fatal errors matter: libxml warns about the uk-* attributes it
        // does not know, which is expected here.
        $fatal = array_filter(libxml_get_errors(), fn ($error) => $error->level === LIBXML_ERR_FATAL);
        libxml_use_internal_errors($previous);

        $this->assertSame([], $fatal, "« {$variant['label']} » produit un markup non analysable");
    }

    #[DataProvider('variants')]
    public function testVariantLeaksNoTranslationKey(string $theme, array $variant): void
    {
        $this->assertStringNotContainsString(
            'pixelopen.kirby-uikit-builder.',
            Theme::render($variant),
            "« {$variant['label']} » laisse fuir une clé de traduction non résolue"
        );
    }
}
