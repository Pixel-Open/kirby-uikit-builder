<?php

namespace PixelOpen\KirbyUikitBuilder;

use Kirby\Cms\File;

class Image
{
    // Largeurs générées pour le srcset des images de contenu
    public const SRCSET_WIDTHS = [480, 768, 1024, 1366, 1600];

    // Largeurs pour les images plein écran (slideshows, covers)
    public const COVER_WIDTHS = [640, 1024, 1366, 1920];

    /**
     * Variantes responsive d'un fichier image.
     *
     * - src : variante de secours (la plus grande générée), jamais l'upload brut
     * - srcset / webpSrcset : chaînes srcset au format d'origine et en WebP
     * - full : grande variante (1920px) pour lightbox ou lien vers l'image
     *
     * Les largeurs supérieures à la largeur réelle du fichier sont écartées.
     * SVG (non redimensionnable) et GIF (animation perdue au resize) sont
     * servis tels quels, sans variantes. Une image plus petite que la
     * première borne reçoit une simple conversion WebP sans srcset.
     *
     * width / height : dimensions intrinsèques de la variante src (attributs
     * anti-CLS), null quand elles sont inconnues (certains SVG).
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
