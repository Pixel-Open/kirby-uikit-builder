<?php

namespace PixelOpen\KirbyUikitBuilder;

use Kirby\Cms\File;

class Image
{
    // Widths generated for the srcset of content images
    public const SRCSET_WIDTHS = [480, 768, 1024, 1366, 1600];

    // Widths for full-screen images (slideshows, covers)
    public const COVER_WIDTHS = [640, 1024, 1366, 1920];

    /**
     * Responsive variants of an image file.
     *
     * - src: fallback variant (the largest one generated), never the raw upload
     * - srcset / webpSrcset: srcset strings in the original format and in WebP
     * - full: large variant (1920px) for a lightbox or a link to the image
     *
     * Widths above the file's actual width are dropped. SVG (not resizable)
     * and GIF (animation lost on resize) are served as is, with no variants.
     * An image smaller than the first breakpoint only gets a WebP conversion,
     * without a srcset.
     *
     * width / height: intrinsic dimensions of the src variant (anti-CLS
     * attributes), null when unknown (some SVGs).
     *
     * @return array{src: string, srcset: string|null, webpSrcset: string|null, full: string, width: int|null, height: int|null}
     */
    public static function sources(File $image, array $widths = self::SRCSET_WIDTHS): array
    {
        $src        = $image->url();
        $srcset     = null;
        $webpSrcset = null;
        $full       = $src;
        $width      = $image->width() ?: null;
        $height     = $image->height() ?: null;

        if ($image->isResizable() && $image->extension() !== 'gif') {
            $fitting = array_filter($widths, fn ($w) => $w <= $image->width());
            if ($fitting) {
                $src    = $image->resize(max($fitting))->url();
                $full   = $image->resize(1920)->url();
                $srcset = $image->srcset($fitting);
                $height = (int)round($image->height() * max($fitting) / $image->width());
                $width  = max($fitting);
                $webpVariants = [];
                foreach ($fitting as $w) {
                    $webpVariants[$w . 'w'] = ['width' => $w, 'format' => 'webp'];
                }
                $webpSrcset = $image->srcset($webpVariants);
            } else {
                $webpSrcset = $image->thumb(['format' => 'webp'])->url();
            }
        }

        return compact('src', 'srcset', 'webpSrcset', 'full', 'width', 'height');
    }
}
