<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Render;

use PHPUnit\Framework\Attributes\DataProvider;
use PixelOpen\KirbyUikitBuilder\Tests\Hostile;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Tests\Theme;

/**
 * Replays the 92 variants with hostile values in every text, URL and color
 * field, and checks that none of them produces executable markup.
 *
 * The 2026-07-07 audit fixed escaping at the injection points (class, style,
 * component attributes); nothing had checked it since. These tests turn that
 * one-off fix into a permanent guard, one that also covers the blocks that do
 * not exist yet.
 */
final class EscapingTest extends TestCase
{
    public static function variants(): array
    {
        $cases = [];

        foreach (Theme::allVariants() as $key => [$theme, $variant]) {
            foreach (Hostile::sets() as $set) {
                $cases["$key ($set)"] = [$theme, $variant, $set];
            }
        }

        return $cases;
    }

    private function dom(string $html): \DOMDocument
    {
        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!doctype html><html lang="fr"><head><meta charset="utf-8"></head><body>' . trim($html) . '</body></html>',
            LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $document;
    }

    /**
     * @return list<\DOMElement>
     */
    private function elements(\DOMDocument $document): array
    {
        return iterator_to_array($document->getElementsByTagName('*'));
    }

    #[DataProvider('variants')]
    public function testHostileContentCreatesNoScriptElement(string $theme, array $variant, string $set): void
    {
        $document = $this->dom(Theme::render(Hostile::variant($variant, $set)));

        $this->assertSame(
            0,
            $document->getElementsByTagName('script')->length,
            "« {$variant['label']} » ($set) laisse une charge hostile créer un élément script"
        );
    }

    #[DataProvider('variants')]
    public function testHostileContentCreatesNoEventHandler(string $theme, array $variant, string $set): void
    {
        $document = $this->dom(Theme::render(Hostile::variant($variant, $set)));
        $found    = [];

        foreach ($this->elements($document) as $element) {
            foreach ($element->attributes ?? [] as $attribute) {
                if (str_starts_with(strtolower($attribute->name), 'on') === true) {
                    $found[] = $element->tagName . '[' . $attribute->name . ']';
                }
            }
        }

        // The code block emits a static onclick for its copy button: it comes
        // from no field, it is written in the snippet.
        $found = array_values(array_filter($found, fn (string $hit) => $hit !== 'button[onclick]'));

        $this->assertSame(
            [],
            $found,
            "« {$variant['label']} » ($set) laisse une charge hostile créer un gestionnaire d'évènement : "
            . implode(', ', $found)
        );
    }

    #[DataProvider('variants')]
    public function testHostileContentCreatesNoExecutableUrl(string $theme, array $variant, string $set): void
    {
        $document = $this->dom(Theme::render(Hostile::variant($variant, $set)));
        $found    = [];

        foreach ($this->elements($document) as $element) {
            foreach (['href', 'src', 'data-src', 'action', 'formaction'] as $name) {
                $value = strtolower(trim($element->getAttribute($name)));

                if (str_starts_with($value, 'javascript:') || str_starts_with($value, 'data:text/html')) {
                    $found[] = $element->tagName . '@' . $name;
                }
            }
        }

        $this->assertSame(
            [],
            $found,
            "« {$variant['label']} » ($set) produit une URL exécutable : " . implode(', ', $found)
        );
    }

    /**
     * A hostile payload must never widen a style attribute: the only expected
     * declarations are the ones the snippets compose themselves. Every payload
     * in the css set carries evil.test, which acts as the marker.
     */
    #[DataProvider('variants')]
    public function testHostileContentInjectsNoStyleDeclaration(string $theme, array $variant, string $set): void
    {
        $document = $this->dom(Theme::render(Hostile::variant($variant, $set)));
        $found    = [];

        foreach ($this->elements($document) as $element) {
            $style = $element->getAttribute('style');

            if ($style !== '' && str_contains(strtolower($style), 'evil.test') === true) {
                $found[] = $element->tagName . ' → ' . $style;
            }
        }

        $this->assertSame(
            [],
            $found,
            "« {$variant['label']} » ($set) laisse une charge hostile écrire dans style : " . implode(', ', $found)
        );
    }

    /**
     * A check on the method itself: without it, a regression in Hostile (a
     * forgotten field type, a blueprint that changes) would leave the whole
     * suite green while injecting nothing at all.
     */
    public function testMutationActuallyReachesTheOutput(): void
    {
        $carte = Hostile::variant(Theme::variant('card', 'media-and-link'));

        $this->assertSame(
            Hostile::PAYLOADS['html']['text'],
            $carte['block']['content']['card_badge'],
            'le champ text card_badge doit avoir été réécrit'
        );
        $this->assertSame(
            Hostile::PAYLOADS['html']['url'],
            $carte['block']['content']['link_url'],
            'le champ url link_url doit avoir été réécrit'
        );

        $rendu = Theme::render($carte);

        $this->assertStringContainsString(
            'script',
            $rendu,
            'la charge doit bien atteindre la sortie, échappée'
        );
        $this->assertStringNotContainsString('<script', $rendu);
    }
}
