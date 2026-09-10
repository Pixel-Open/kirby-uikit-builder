<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use Kirby\Cms\Layout;
use PixelOpen\KirbyUikitBuilder\Section;
use PixelOpen\KirbyUikitBuilder\Tests\Fixture;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;

/**
 * Section::prepare() assembles a section's classes and, above all, builds the
 * style="" attributes from values entered in the Panel. safeHex() and
 * safeGradientDir() are the only safeguards on that path: the injection tests
 * below pin their contract.
 */
final class SectionTest extends TestCase
{
    private function prepare(array $attrs = [], bool $withParent = false): array
    {
        $params = ['attrs' => $attrs];

        if ($withParent === true) {
            $params['parent'] = Fixture::page();
        }

        return Section::prepare(new Layout($params));
    }

    // --- Bare section ------------------------------------------------------

    public function testBareSectionProducesNoStyleAndNoAttributes(): void
    {
        $section = $this->prepare();

        $this->assertSame('uk-section', $section['classes']);
        $this->assertSame('uk-container', $section['containerClass']);
        $this->assertNull($section['sectionStyle']);
        $this->assertNull($section['overlayStyle']);
        $this->assertNull($section['scrollspyAttr']);
        $this->assertNull($section['srcset']);
        $this->assertNull($section['bgImage']);
        $this->assertFalse($section['hasVideo']);
    }

    public function testDefaultsAreAppliedOnEmptyValues(): void
    {
        $section = $this->prepare([
            'parallax_speed' => '',
            'grid_gap'       => '',
            'shape_divider'  => 'true',
        ]);

        $this->assertSame(-200, $section['parallaxSpeed']);
        $this->assertSame('uk-grid-large', $section['gridGap']);
        $this->assertSame('curve', $section['shapeDividerType']);
        $this->assertSame('bottom', $section['shapeDividerPos']);
        $this->assertSame('#ffffff', $section['shapeDividerColor']);
        $this->assertSame('150px', $section['shapeDividerHeight']);
    }

    // --- Custom background -------------------------------------------------

    public function testCustomBackgroundColour(): void
    {
        $section = $this->prepare([
            'background'      => 'custom',
            'bg_custom_color' => '#fff',
        ]);

        $this->assertSame('background-color: #fff', $section['sectionStyle']);
        // "custom" is a UI marker, not a UIkit class: it must never land in
        // the class attribute.
        $this->assertStringNotContainsString('custom', $section['classes']);
    }

    public function testCustomBackgroundGradient(): void
    {
        $section = $this->prepare([
            'background'         => 'custom',
            'bg_custom_gradient' => 'true',
            'bg_custom_color'    => '#000000',
            'bg_custom_color2'   => '#ffffffcc',
            'bg_gradient_dir'    => 'to bottom right',
        ]);

        $this->assertSame(
            'background-image: linear-gradient(to bottom right, #000000, #ffffffcc)',
            $section['sectionStyle']
        );
    }

    public function testGradientWithoutSecondColourFallsBackToFlatColour(): void
    {
        $section = $this->prepare([
            'background'         => 'custom',
            'bg_custom_gradient' => 'true',
            'bg_custom_color'    => '#123456',
            'bg_custom_color2'   => '',
        ]);

        $this->assertSame('background-color: #123456', $section['sectionStyle']);
    }

    public function testGradientIsIgnoredWhenBackgroundIsNotCustom(): void
    {
        $section = $this->prepare([
            'background'         => 'uk-section-muted',
            'bg_custom_gradient' => 'true',
            'bg_custom_color'    => '#000000',
            'bg_custom_color2'   => '#ffffff',
        ]);

        $this->assertNull($section['sectionStyle']);
        $this->assertStringContainsString('uk-section-muted', $section['classes']);
    }

    // --- Injection safeguards -----------------------------------------------

    /**
     * @param string $colour value as it could come out of the Panel
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalidColours')]
    public function testInvalidColourProducesNoStyleAtAll(string $colour): void
    {
        $section = $this->prepare([
            'background'      => 'custom',
            'bg_custom_color' => $colour,
        ]);

        $this->assertNull($section['sectionStyle'], "la couleur « $colour » ne doit produire aucun style");
    }

    public static function invalidColours(): array
    {
        return [
            'mot-clé CSS'         => ['red'],
            'trop court'          => ['#12'],
            'trop long'           => ['#1234567890'],
            'caractère hors hexa' => ['#gggggg'],
            'sans dièse'          => ['ffffff'],
            'déclaration greffée' => ['#f00; background-image: url(//evil.test/x.png)'],
            'fermeture d\'attribut' => ['#f00" onload="alert(1)'],
            'expression'          => ['expression(alert(1))'],
            'vide'                => [''],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validColours')]
    public function testValidColourIsAccepted(string $colour): void
    {
        $section = $this->prepare([
            'background'      => 'custom',
            'bg_custom_color' => $colour,
        ]);

        $this->assertSame("background-color: $colour", $section['sectionStyle']);
    }

    public static function validColours(): array
    {
        return [
            'court'      => ['#abc'],
            'long'       => ['#a1b2c3'],
            'majuscules' => ['#ABCDEF'],
            'alpha'      => ['#a1b2c3ff'],
        ];
    }

    public function testInvalidGradientDirectionFallsBackToDefault(): void
    {
        $section = $this->prepare([
            'background'         => 'custom',
            'bg_custom_gradient' => 'true',
            'bg_custom_color'    => '#000000',
            'bg_custom_color2'   => '#ffffff',
            'bg_gradient_dir'    => 'to right); background-image: url(//evil.test/x.png',
        ]);

        $this->assertSame(
            'background-image: linear-gradient(to right, #000000, #ffffff)',
            $section['sectionStyle']
        );
    }

    public function testRotationSyntaxIsNotAValidDirection(): void
    {
        $section = $this->prepare([
            'background'         => 'custom',
            'bg_custom_gradient' => 'true',
            'bg_custom_color'    => '#000000',
            'bg_custom_color2'   => '#ffffff',
            'bg_gradient_dir'    => '45deg',
        ]);

        $this->assertStringContainsString('linear-gradient(to right,', $section['sectionStyle']);
    }

    // --- Overlay -----------------------------------------------------------

    public function testOverlayConvertsHexToRgba(): void
    {
        $section = $this->prepare([
            'overlay_color'   => '#000000',
            'overlay_opacity' => 40,
        ]);

        $this->assertSame('background-color: rgba(0, 0, 0, 0.40)', $section['overlayStyle']);
        $this->assertStringContainsString('uk-position-relative', $section['classes']);
    }

    public function testOverlayExpandsShorthandHex(): void
    {
        $section = $this->prepare([
            'overlay_color'   => '#abc',
            'overlay_opacity' => 100,
        ]);

        $this->assertSame('background-color: rgba(170, 187, 204, 1.00)', $section['overlayStyle']);
    }

    public function testOverlayOpacityDefaultsToForty(): void
    {
        $section = $this->prepare(['overlay_color' => '#ffffff', 'overlay_opacity' => '']);

        $this->assertSame('background-color: rgba(255, 255, 255, 0.40)', $section['overlayStyle']);
    }

    public function testOverlayGradientUsesItsOwnDirection(): void
    {
        $section = $this->prepare([
            'overlay_color'        => '#000000',
            'overlay_color2'       => '#ffffff',
            'overlay_gradient'     => 'true',
            'overlay_gradient_dir' => 'to top',
            'overlay_opacity'      => 50,
        ]);

        $this->assertSame(
            'background-image: linear-gradient(to top, rgba(0, 0, 0, 0.50), rgba(255, 255, 255, 0.50))',
            $section['overlayStyle']
        );
    }

    public function testInvalidOverlayColourProducesNoOverlay(): void
    {
        $section = $this->prepare(['overlay_color' => 'rgba(0,0,0,.5)']);

        $this->assertNull($section['overlayStyle']);
        $this->assertStringNotContainsString('uk-position-relative', $section['classes']);
    }

    // --- Class composition --------------------------------------------------

    public function testClassesCombineEveryOptionInOrder(): void
    {
        $section = $this->prepare([
            'background'     => 'uk-section-muted',
            'padding'        => 'uk-section-large',
            'text_color'     => 'uk-light',
            'visibility'     => 'uk-visible@m',
            'padding_remove' => 'uk-padding-remove-top, uk-padding-remove-bottom',
        ]);

        $this->assertSame(
            'uk-section uk-section-muted uk-section-large uk-light uk-visible@m '
            . 'uk-padding-remove-top uk-padding-remove-bottom',
            $section['classes']
        );
    }

    public function testExtraClassesMergeBothFieldsAndDropEmptyEntries(): void
    {
        $section = $this->prepare([
            'css_classes'        => 'uk-section-divider, uk-overflow-hidden',
            'css_classes_custom' => '  ma-classe   une-autre  ',
        ]);

        $this->assertSame(
            ['uk-section-divider', 'uk-overflow-hidden', 'ma-classe', 'une-autre'],
            array_values($section['extraClasses'])
        );
        $this->assertStringEndsWith('uk-section-divider uk-overflow-hidden ma-classe une-autre', $section['classes']);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('containers')]
    public function testContainerClass(string $value, string $expected): void
    {
        $this->assertSame($expected, $this->prepare(['container' => $value])['containerClass']);
    }

    public static function containers(): array
    {
        return [
            // The modifier adds to uk-container, it does not replace it.
            'normal' => ['', 'uk-container'],
            'étroit' => ['small', 'uk-container uk-container-small'],
            'large'  => ['large', 'uk-container uk-container-large'],
            'pleine largeur' => ['expand', 'uk-container uk-container-expand'],
        ];
    }

    // --- Scrollspy ---------------------------------------------------------

    public function testScrollspyIsOffByDefault(): void
    {
        $this->assertNull($this->prepare(['scrollspy_cls' => 'uk-animation-fade'])['scrollspyAttr']);
    }

    public function testScrollspyAttribute(): void
    {
        $section = $this->prepare(['scrollspy' => 'true']);

        $this->assertSame('uk-scrollspy="cls: uk-animation-fade; delay: 0"', $section['scrollspyAttr']);
    }

    public function testScrollspyWithDelayAndRepeat(): void
    {
        $section = $this->prepare([
            'scrollspy'        => 'true',
            'scrollspy_cls'    => 'uk-animation-slide-left-medium',
            'scrollspy_delay'  => 300,
            'scrollspy_repeat' => 'true',
        ]);

        $this->assertSame(
            'uk-scrollspy="cls: uk-animation-slide-left-medium; delay: 300; repeat: true"',
            $section['scrollspyAttr']
        );
    }

    public function testScrollspyClassIsEscaped(): void
    {
        $section = $this->prepare([
            'scrollspy'     => 'true',
            'scrollspy_cls' => 'fade" onmouseover="alert(1)',
        ]);

        $this->assertStringNotContainsString('onmouseover="alert', $section['scrollspyAttr']);
        $this->assertStringContainsString('&quot;', $section['scrollspyAttr']);
    }

    // --- Background video ---------------------------------------------------

    public function testYoutubeUrlIsRewrittenToTheNoCookieDomain(): void
    {
        $section = $this->prepare([
            'bg_video_enable' => 'true',
            'bg_video_source' => 'url',
            'bg_video_url'    => 'https://www.youtube.com/embed/abc123',
        ]);

        $this->assertSame('https://www.youtube-nocookie.com/embed/abc123', $section['bgVideoUrl']);
        $this->assertTrue($section['hasVideo']);
        $this->assertStringContainsString('uk-cover-container', $section['classes']);
        $this->assertStringContainsString('uk-position-relative', $section['classes']);
    }

    public function testVideoUrlIsIgnoredWhenTheToggleIsOff(): void
    {
        $section = $this->prepare([
            'bg_video_enable' => 'false',
            'bg_video_source' => 'url',
            'bg_video_url'    => 'https://www.youtube.com/embed/abc123',
        ]);

        $this->assertNull($section['bgVideoUrl']);
        $this->assertFalse($section['hasVideo']);
    }

    public function testVideoUrlIsIgnoredWhenTheSourceIsAFile(): void
    {
        $section = $this->prepare([
            'bg_video_enable' => 'true',
            'bg_video_source' => 'file',
            'bg_video_url'    => 'https://www.youtube.com/embed/abc123',
        ]);

        $this->assertNull($section['bgVideoUrl']);
        $this->assertFalse($section['hasVideo']);
    }

    // --- Background image ---------------------------------------------------

    public function testBackgroundImageAddsCoverClassAndSrcset(): void
    {
        $section = $this->prepare(['bg_image' => 'slider-1.jpg'], withParent: true);

        $this->assertNotNull($section['bgImage']);
        $this->assertStringContainsString('uk-background-cover', $section['classes']);

        foreach (Section::BG_SRCSET_WIDTHS as $width) {
            $this->assertStringContainsString($width . 'w', $section['srcset']);
        }
    }

    public function testBackgroundVideoFileIsResolved(): void
    {
        $section = $this->prepare([
            'bg_video_enable' => 'true',
            'bg_video_source' => 'file',
            'bg_video_file'   => 'video.mp4',
        ], withParent: true);

        $this->assertNotNull($section['bgVideoFile']);
        $this->assertSame('video.mp4', $section['bgVideoFile']->filename());
        $this->assertNull($section['bgVideoUrl']);
        $this->assertTrue($section['hasVideo']);
        $this->assertStringContainsString('uk-cover-container', $section['classes']);
    }

    public function testVideoFileIsIgnoredWhenTheSourceIsAnUrl(): void
    {
        $section = $this->prepare([
            'bg_video_enable' => 'true',
            'bg_video_source' => 'url',
            'bg_video_file'   => 'video.mp4',
            'bg_video_url'    => '',
        ], withParent: true);

        $this->assertNull($section['bgVideoFile']);
        $this->assertFalse($section['hasVideo']);
    }

    // --- Options passed straight through ------------------------------------

    /**
     * Every property prepare() reads has to come back out in the final
     * compact(): forgetting one raises no error, it only leaves a Panel setting
     * that silently stops having any effect in the snippet.
     */
    public function testGridAndAdvancedOptionsArePassedThrough(): void
    {
        $section = $this->prepare([
            'grid_valign'   => 'uk-flex-middle',
            'grid_halign'   => 'uk-flex-center',
            'grid_gap'      => 'uk-grid-small',
            'grid_divider'  => 'true',
            'parallax'      => 'true',
            'parallax_speed' => 350,
            'eager_image'   => 'true',
            'section_id'    => 'contact',
            'aria_label'    => 'Nous contacter',
        ]);

        $this->assertSame('uk-flex-middle', $section['gridValign']);
        $this->assertSame('uk-flex-center', $section['gridHalign']);
        $this->assertSame('uk-grid-small', $section['gridGap']);
        $this->assertTrue($section['gridDivider']);
        $this->assertTrue($section['parallax']);
        $this->assertSame(350, $section['parallaxSpeed']);
        $this->assertTrue($section['eagerImage']);
        $this->assertSame('contact', $section['sectionId']);
        $this->assertSame('Nous contacter', $section['ariaLabel']);
    }

    /**
     * The divider is drawn absolutely positioned over the section: without
     * height reserved on the container, the content slides under it.
     */
    public function testShapeDividerReservesRoomForItself(): void
    {
        $bas = $this->prepare(['shape_divider' => 'true', 'shape_divider_position' => 'bottom']);
        $this->assertSame('padding-bottom: 150px;', $bas['containerStyle']);

        $haut = $this->prepare([
            'shape_divider'          => 'true',
            'shape_divider_position' => 'top',
            'shape_divider_height'   => '90px',
        ]);
        $this->assertSame('padding-top: 90px;', $haut['containerStyle']);

        $deux = $this->prepare([
            'shape_divider'          => 'true',
            'shape_divider_position' => 'both',
            'shape_divider_height'   => '4rem',
        ]);
        $this->assertSame('padding-top: 4rem;padding-bottom: 4rem;', $deux['containerStyle']);

        $this->assertNull($this->prepare()['containerStyle'], 'sans séparateur, aucun style');
    }

    public function testInvalidDividerHeightFallsBackToTheDefault(): void
    {
        $section = $this->prepare([
            'shape_divider'        => 'true',
            'shape_divider_height' => '150px;position:fixed;top:0',
        ]);

        $this->assertSame('150px', $section['shapeDividerHeight']);
        $this->assertSame('padding-bottom: 150px;', $section['containerStyle']);
    }

    public function testShapeDividerMakesTheSectionPositioned(): void
    {
        $section = $this->prepare(['shape_divider' => 'true']);

        $this->assertTrue($section['shapeDivider']);
        $this->assertStringContainsString('uk-position-relative', $section['classes']);
    }

    public function testVideoTakesPrecedenceOverBackgroundImage(): void
    {
        $section = $this->prepare([
            'bg_image'        => 'slider-1.jpg',
            'bg_video_enable' => 'true',
            'bg_video_source' => 'url',
            'bg_video_url'    => 'https://vimeo.test/embed/1',
        ], withParent: true);

        $this->assertTrue($section['hasVideo']);
        $this->assertStringContainsString('uk-cover-container', $section['classes']);
        $this->assertStringNotContainsString('uk-background-cover', $section['classes']);
    }
}
