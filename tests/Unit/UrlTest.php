<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;
use PixelOpen\KirbyUikitBuilder\Url;

/**
 * Url::safe() is the only barrier between a Panel url field and an href.
 * Escaping plays no part here: htmlspecialchars('javascript:alert(1)') leaves
 * the string untouched and the link still runs on click.
 */
final class UrlTest extends TestCase
{
    #[DataProvider('accepted')]
    public function testSafeUrlIsReturnedUnchanged(string $url): void
    {
        $this->assertSame($url, Url::safe($url));
    }

    public static function accepted(): array
    {
        return [
            'https'                 => ['https://example.test/page'],
            'http'                  => ['http://example.test'],
            'mailto'                => ['mailto:bonjour@example.test'],
            'tel'                   => ['tel:+33123456789'],
            'chemin absolu'         => ['/contact'],
            'chemin relatif'        => ['contact/nous'],
            'ancre'                 => ['#tarifs'],
            'requête'               => ['?page=2'],
            'relatif au protocole'  => ['//cdn.example.test/x.pdf'],
            'majuscules de schéma'  => ['HTTPS://example.test'],
        ];
    }

    #[DataProvider('rejected')]
    public function testDangerousSchemeIsRejected(string $url): void
    {
        $this->assertNull(Url::safe($url), "« $url » ne doit produire aucun href");
    }

    public static function rejected(): array
    {
        return [
            'javascript'            => ['javascript:alert(1)'],
            'javascript en casse'   => ['JaVaScRiPt:alert(1)'],
            'vbscript'              => ['vbscript:msgbox(1)'],
            'data html'             => ['data:text/html;base64,PHNjcmlwdD4='],
            'schéma inconnu'        => ['exotique:charge'],
            // Browsers ignore whitespace and control characters before the
            // colon: these three forms run despite how they look.
            'tabulation intercalée' => ["java\tscript:alert(1)"],
            'saut de ligne'         => ["java\nscript:alert(1)"],
            'espaces en tête'       => ['  javascript:alert(1)'],
            'octet nul'             => ["java\0script:alert(1)"],
        ];
    }

    #[DataProvider('empty')]
    public function testEmptyValuesBecomeNull(?string $url): void
    {
        $this->assertNull(Url::safe($url));
    }

    public static function empty(): array
    {
        return ['null' => [null], 'chaîne vide' => [''], 'espaces' => ['   ']];
    }
}
