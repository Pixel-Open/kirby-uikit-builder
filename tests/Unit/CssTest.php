<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PixelOpen\KirbyUikitBuilder\Css;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;

/**
 * Css::length() guards the Panel's free-text height fields, which end up in a
 * style attribute. htmlspecialchars is not enough there: it lets the semicolon
 * through, and therefore the declaration that follows.
 */
final class CssTest extends TestCase
{
    #[DataProvider('accepted')]
    public function testValidLengthIsReturnedUnchanged(string $value): void
    {
        $this->assertSame($value, Css::length($value));
    }

    public static function accepted(): array
    {
        return [
            'pixels'     => ['150px'],
            'rem'        => ['12rem'],
            'em'         => ['3em'],
            'pourcents'  => ['50%'],
            'viewport'   => ['80vh'],
            'décimale'   => ['12.5px'],
            'zéro nu'    => ['0'],
            'majuscules' => ['150PX'],
        ];
    }

    #[DataProvider('rejected')]
    public function testInvalidLengthBecomesNull(?string $value): void
    {
        $this->assertNull(Css::length($value), "« $value » ne doit pas atterrir dans un style");
    }

    public static function rejected(): array
    {
        return [
            'déclaration greffée' => ['150px;position:fixed;top:0'],
            'fermeture d\'attribut' => ['150px" onload="alert(1)'],
            'unité inconnue'      => ['150foo'],
            'sans unité'          => ['150'],
            'expression'          => ['calc(100% - 10px)'],
            'négatif'             => ['-10px'],
            'mot-clé'             => ['auto'],
            'vide'                => [''],
            'null'                => [null],
        ];
    }
}
