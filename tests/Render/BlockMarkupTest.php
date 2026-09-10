<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Render;

use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Tests\Theme;

/**
 * Markup contracts that snapshots could not defend on their own: a snapshot
 * reports that a rendering changed, never that it stopped being correct. Every
 * assertion below covers a point that UIkit's behaviour, accessibility or link
 * resolution depends on.
 */
final class BlockMarkupTest extends TestCase
{
    private function xpath(string $theme, string $variant): \DOMXPath
    {
        $html = trim(Theme::render(Theme::variant($theme, $variant)));

        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!doctype html><html lang="fr"><head><meta charset="utf-8"></head><body>' . $html . '</body></html>',
            LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new \DOMXPath($document);
    }

    private function assertCount_(int $expected, \DOMXPath $xpath, string $query, string $message = ''): void
    {
        $this->assertSame($expected, $xpath->query($query)->length, $message);
    }

    // --- Composite links ----------------------------------------------------

    public function testAnchorButtonKeepsSmoothScrolling(): void
    {
        $xpath = $this->xpath('content', 'button-anchor');

        // A regression that already happened: uk-scroll stuck to role="button"
        // lost both attributes (see changelog 1.0.1).
        $this->assertCount_(1, $xpath, '//a[@href="#tarifs"][@uk-scroll][@role="button"]',
            'le bouton d\'ancre doit garder uk-scroll et role="button" séparés');
    }

    public function testMailtoAndTelLinksAreBuiltFromTheirOwnFields(): void
    {
        $courriel = $this->xpath('content', 'button-mailto');
        $this->assertCount_(1, $courriel, '//a[@href="mailto:contact@example.test"]',
            'link_type email doit produire un mailto:');

        $cta = $this->xpath('cta', 'primary-right-aligned');
        $this->assertCount_(1, $cta, '//a[@href="tel:+33123456789"]',
            'link_type telephone doit produire un tel:');
    }

    public function testExternalButtonCarriesNoopener(): void
    {
        $xpath = $this->xpath('content', 'button-new-tab');

        $this->assertCount_(1, $xpath, '//a[@target="_blank"][@rel="noopener"]',
            'un lien en nouvel onglet doit porter rel="noopener"');
    }

    public function testPricingResolvesOneLinkPerRow(): void
    {
        $xpath = $this->xpath('pricing', 'three-plans');

        // linkHref is registered on structure rows: every plan resolves its
        // own link type.
        $this->assertCount_(1, $xpath, '//a[@href="https://example.test/essentiel"]', 'lien url de la 1re offre');
        $this->assertCount_(1, $xpath, '//a[@href="#contact"]', 'lien ancre de la 2e offre');
        $this->assertCount_(1, $xpath, '//a[@href="mailto:devis@example.test"]', 'lien courriel de la 3e offre');
    }

    // --- Images -------------------------------------------------------------

    public function testResponsiveImageCarriesWebpAndIntrinsicDimensions(): void
    {
        $xpath = $this->xpath('image', 'ratio-lightbox');

        $this->assertCount_(1, $xpath, '//picture/source[@type="image/webp"][@data-srcset]',
            'une source WebP doit précéder le img');
        // libxml parses as HTML4 and closes <picture> when it meets <img>: the
        // img is therefore a sibling of the source, not its child. An artefact
        // of the test parser, not of the markup produced.
        $this->assertCount_(1, $xpath, '//img[@width][@height]',
            'width et height sont les attributs anti-CLS, ils ne sont pas optionnels');
        $this->assertCount_(1, $xpath, '//a[@uk-lightbox]', 'la lightbox doit envelopper l\'image');
    }

    public function testEagerImageOptsOutOfLazyLoading(): void
    {
        $lazy  = $this->xpath('image', 'centered');
        $eager = $this->xpath('image', 'left-aligned-link');

        $this->assertCount_(1, $lazy, '//img[@data-src]',
            'par défaut l\'image est différée par uk-img, donc data-src');
        $this->assertCount_(1, $eager, '//img[@src]',
            'en mode eager l\'image doit porter un src immédiat');
    }

    public function testGalleryCropsIntoALightbox(): void
    {
        $variant = Theme::variant('gallery', 'three-columns-cropped');
        $attendu = count($variant['block']['content']['gallery']);

        $this->assertCount_($attendu, $this->xpath('gallery', 'three-columns-cropped'), '//a[@href]//img',
            'chaque image de la galerie est un lien de lightbox');
    }

    /**
     * Masonry serves every image at its natural height. The attributes have to
     * describe the variant served, and the image has to fill its column even
     * when the original is smaller than the resize breakpoint.
     */
    public function testMasonryKeepsEachImageAtItsOwnHeight(): void
    {
        $xpath = $this->xpath('gallery', 'masonry');

        $this->assertCount_(1, $xpath, '//*[@uk-grid="masonry: true"]');

        $ratios = [];
        foreach ($xpath->query('//img[@width][@height]') as $img) {
            $ratios[] = round((int)$img->getAttribute('width') / (int)$img->getAttribute('height'), 2);
            $this->assertStringContainsString('uk-width-1-1', $img->getAttribute('class'));
        }

        $this->assertGreaterThan(
            2,
            count(array_unique($ratios)),
            'une mosaïque de proportions identiques ne se distingue pas d\'une grille'
        );
    }

    // --- UIkit components ---------------------------------------------------

    public function testSliderDeclaresSlideshowAndOneItemPerSlide(): void
    {
        $xpath = $this->xpath('slider', 'fade-dotnav');

        $this->assertCount_(1, $xpath, '//*[@uk-slideshow]', 'le composant uk-slideshow doit être déclaré');
        $this->assertCount_(2, $xpath, '//ul[contains(@class,"uk-slideshow-items")]/li',
            'une diapositive par entrée de la structure');
        $this->assertCount_(1, $xpath, '//*[contains(@class,"uk-dotnav")]',
            'navigation_type dotnav doit produire une uk-dotnav');
    }

    public function testSliderThumbnailsReplaceDotnav(): void
    {
        $xpath = $this->xpath('slider', 'slide-thumbnails');

        $this->assertCount_(0, $xpath, '//*[contains(@class,"uk-dotnav")]',
            'le mode vignettes ne doit pas laisser de dotnav');
        $this->assertCount_(3, $xpath, '//ul[contains(@class,"uk-slideshow-items")]/li');
    }

    public function testCarouselDeclaresSliderAndChildWidths(): void
    {
        $xpath = $this->xpath('carousel', 'three-columns');

        $this->assertCount_(1, $xpath, '//*[@uk-slider]');
        $this->assertCount_(1, $xpath, '//ul[contains(@class,"uk-child-width-1-3@m")]',
            'cols_desktop doit se traduire en uk-child-width-1-3@m');
        $this->assertCount_(4, $xpath, '//ul[contains(@class,"uk-slider-items")]/li');
    }

    public function testAccordionMultipleOptionIsPassedToUikit(): void
    {
        $simple   = $this->xpath('faq', 'accordion-single');
        $multiple = $this->xpath('faq', 'accordion-multiple');

        $this->assertCount_(1, $simple, '//ul[@uk-accordion=""]',
            'sans option, uk-accordion reste un attribut nu');
        $this->assertCount_(1, $multiple, '//ul[@uk-accordion="multiple: true"]');
        $this->assertCount_(1, $simple, '//li[contains(@class,"uk-open")]',
            'first_open doit ouvrir le premier volet, et lui seul');
    }

    public function testAccordionRendersItsBody(): void
    {
        $xpath = $this->xpath('faq', 'accordion-single');

        // content() is a StructureObject method: the field of the same name is
        // only reachable through ->get('content'). Forgetting it made the
        // rendering fatal.
        $bodies = $xpath->query('//div[contains(@class,"uk-accordion-content")]');
        $this->assertSame(2, $bodies->length);
        $this->assertNotSame('', trim($bodies->item(0)->textContent),
            'le corps du volet ne doit pas être vide');
    }

    public function testTabsBuildOneNavItemPerPanel(): void
    {
        $xpath = $this->xpath('tabs', 'horizontal');

        $this->assertCount_(3, $xpath, '//ul[@uk-tab or contains(@class,"uk-tab")]/li');
        $this->assertCount_(3, $xpath, '//ul[contains(@class,"uk-switcher")]/li',
            'autant de panneaux que d\'onglets, sinon le switcher se décale');
    }

    public function testTableHeaderMatchesRowWidth(): void
    {
        $xpath = $this->xpath('table', 'striped-hover');

        $this->assertCount_(3, $xpath, '//thead/tr/th');
        $this->assertCount_(3, $xpath, '//tbody/tr');
        $this->assertCount_(9, $xpath, '//tbody/tr/td', '3 lignes de 3 cellules');
        $this->assertCount_(1, $xpath, '//caption', 'la légende du tableau porte l\'accessibilité');
        $this->assertCount_(1, $xpath, '//th[contains(@class,"uk-table-shrink")]');
    }

    public function testClosableAlertHasACloseControl(): void
    {
        $fermable = $this->xpath('alerts', 'success-closable');
        $fixe     = $this->xpath('alerts', 'warning-no-title');

        $this->assertCount_(1, $fermable, '//*[@uk-close]');
        $this->assertCount_(0, $fixe, '//*[@uk-close]');
        $this->assertCount_(1, $fixe, '//div[contains(@class,"uk-alert-warning")]');
    }

    // --- Equal-height grids -------------------------------------------------

    public function testCardGridsUseGridMatch(): void
    {
        // Fixed in 1.0.1: without uk-grid-match, the cards on one row do not
        // share the same height.
        foreach ([['icon-box', 'three-columns'], ['team', 'four-columns'], ['stats', 'default']] as [$theme, $variant]) {
            $this->assertCount_(
                1,
                $this->xpath($theme, $variant),
                '//div[contains(@class,"uk-grid-match")]',
                "$theme/$variant doit porter uk-grid-match"
            );
        }
    }

    // --- Video --------------------------------------------------------------

    public function testYoutubeEmbedUsesTheNoCookieDomainAndATitle(): void
    {
        $xpath = $this->xpath('video', 'youtube');

        $this->assertCount_(1, $xpath, '//iframe[starts-with(@src,"https://www.youtube-nocookie.com/embed/")]');
        $this->assertCount_(1, $xpath, '//iframe[@title][@loading="lazy"]',
            'un iframe sans title est inaccessible au lecteur d\'écran');
    }

    // --- Section ------------------------------------------------------------

    public function testSectionCarriesIdAndAriaLabel(): void
    {
        $xpath = $this->xpath('layouts', 'desktop-only');

        $this->assertCount_(1, $xpath, '//section[@id="bureau-seulement"][@aria-label="Contenu complémentaire"]');
        $this->assertCount_(1, $xpath, '//section[contains(@class,"uk-visible@m")]');
    }

    public function testSectionOverlayIsAnAbsolutelyPositionedLayer(): void
    {
        $xpath = $this->xpath('layouts', 'background-image-overlay');

        $this->assertCount_(1, $xpath,
            '//section[contains(@class,"uk-position-relative")]/div[contains(@class,"uk-position-cover")]',
            'le voile doit être un calque couvrant dans une section positionnée');
        $this->assertCount_(1, $xpath, '//section[@data-srcset][@uk-img]',
            'l\'image de fond passe par uk-img, pas par un background-image en dur');
    }

    public function testShapeDividerOnBothSidesProducesTwoSvg(): void
    {
        $bas  = $this->xpath('dividers', 'waves');
        $deux = $this->xpath('dividers', 'top-and-bottom');

        $this->assertCount_(1, $bas, '//section//svg');
        $this->assertCount_(2, $deux, '//section//svg', 'position « both » doit rendre deux séparateurs');
    }

    /**
     * preserveAspectRatio="none" is useless if the SVG has no box to fill:
     * without a height it takes its viewBox ratio, and the container crops the
     * shape instead of stretching it to the requested height.
     */
    public function testShapeDividerSvgFillsItsWrapper(): void
    {
        $xpath = $this->xpath('dividers', 'waves');

        $this->assertCount_(1, $xpath, '//svg[contains(@style,"height:100%")][@preserveaspectratio="none"]');
        $this->assertCount_(1, $xpath,
            '//div[contains(@style,"height:150px")][contains(@style,"overflow:hidden")]',
            'le conteneur porte la hauteur, le SVG la remplit');
    }

    public function testShapeDividerReservesRoomSoContentDoesNotRunUnder(): void
    {
        $xpath = $this->xpath('dividers', 'custom-height');

        $this->assertCount_(1, $xpath,
            '//div[contains(@class,"uk-container")][contains(@style,"padding-bottom: 220px")]');
    }

    public function testColumnOptionsTurnAColumnIntoACard(): void
    {
        $xpath = $this->xpath('layouts', 'columns-as-cards');

        $this->assertCount_(1, $xpath,
            '//div[contains(@class,"uk-card")][contains(@class,"uk-card-primary")][contains(@class,"uk-card-hover")]');
        $this->assertCount_(1, $xpath, '//div[contains(@class,"uk-tile-muted")]');
    }

    public function testPerColumnAnimationsCarryTheirOwnDelay(): void
    {
        $xpath = $this->xpath('animations', 'staggered-columns');

        $this->assertCount_(1, $xpath, '//div[@uk-scrollspy="cls: uk-animation-slide-left-medium; delay: 0"]');
        $this->assertCount_(1, $xpath, '//div[@uk-scrollspy="cls: uk-animation-slide-bottom-medium; delay: 150"]');
        $this->assertCount_(1, $xpath, '//div[@uk-scrollspy="cls: uk-animation-slide-right-medium; delay: 300"]');
    }

    public function testParallaxIsOnlyAppliedToABackgroundImage(): void
    {
        $xpath = $this->xpath('animations', 'parallax');

        $this->assertCount_(1, $xpath, '//section[@uk-parallax="bgy: -300"]');
    }
}
