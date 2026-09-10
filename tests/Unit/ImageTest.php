<?php

namespace PixelOpen\KirbyUikitBuilder\Tests\Unit;

use PixelOpen\KirbyUikitBuilder\Image;
use PixelOpen\KirbyUikitBuilder\Tests\Fixture;
use PixelOpen\KirbyUikitBuilder\Tests\TestCase;

/**
 * Image::sources() is the single entry point for content images: snippets must
 * never call thumb() themselves. These tests pin its contract on the four
 * families of files an editor can upload.
 */
final class ImageTest extends TestCase
{
    public function testLargeImageGetsFullSrcset(): void
    {
        $sources = Image::sources(Fixture::page()->image('slider-1.jpg'));

        $this->assertNotNull($sources['srcset']);
        $this->assertNotNull($sources['webpSrcset']);

        // src is the largest generated variant, never the raw upload
        $this->assertStringNotContainsString('/slider-1.jpg', $sources['src']);
        $this->assertStringContainsString('1600', $sources['src']);

        foreach (Image::SRCSET_WIDTHS as $width) {
            $this->assertStringContainsString($width . 'w', $sources['srcset']);
        }
        $this->assertStringContainsString('.webp', $sources['webpSrcset']);
    }

    public function testLargeImageReportsDimensionsOfItsSrcVariant(): void
    {
        $sources = Image::sources(Fixture::page()->image('slider-1.jpg'));

        // The anti-CLS attributes have to describe the variant served (1600
        // wide), not the original file (2000), otherwise the ratio is wrong.
        $this->assertSame(1600, $sources['width']);
        $this->assertSame(897, $sources['height']);
    }

    public function testSmallImageGetsWebpButNoSrcset(): void
    {
        $image   = Fixture::page()->image('small-image.jpg');
        $sources = Image::sources($image);

        $this->assertNull($sources['srcset'], 'aucune borne ne tient dans une image de 300px');
        $this->assertNotNull($sources['webpSrcset']);
        $this->assertStringContainsString('.webp', $sources['webpSrcset']);
        $this->assertSame($image->url(), $sources['src']);
        $this->assertSame(300, $sources['width']);
        $this->assertSame(212, $sources['height']);
    }

    public function testSvgIsServedAsIs(): void
    {
        $image   = Fixture::page()->image('logo-1.svg');
        $sources = Image::sources($image);

        $this->assertSame($image->url(), $sources['src']);
        $this->assertSame($image->url(), $sources['full']);
        $this->assertNull($sources['srcset']);
        $this->assertNull($sources['webpSrcset']);
    }

    public function testGifIsServedAsIsToPreserveAnimation(): void
    {
        $image   = Fixture::page()->image('anim.gif');
        $sources = Image::sources($image);

        $this->assertSame($image->url(), $sources['src']);
        $this->assertNull($sources['srcset']);
        $this->assertNull($sources['webpSrcset']);
        $this->assertSame(800, $sources['width']);
        $this->assertSame(600, $sources['height']);
    }

    public function testCoverWidthsAreHonoured(): void
    {
        $sources = Image::sources(Fixture::page()->image('slider-1.jpg'), Image::COVER_WIDTHS);

        $this->assertSame(1920, $sources['width']);
        $this->assertStringContainsString('1920w', $sources['srcset']);
        $this->assertStringNotContainsString('1600w', $sources['srcset']);
    }
}
