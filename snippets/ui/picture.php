<?php
// Rendu <picture> responsive minimal (srcset original + WebP), pour les
// snippets qui gèrent eux-mêmes leur wrapper (slider, carousel, card…).
// Pour un rendu complet (ratio, lightbox, lien, légende), voir ui/image.
$image  ??= null;  // Kirby\Cms\File
$sizes  ??= '100vw';
$widths ??= \PixelOpen\KirbyUikitBuilder\Image::SRCSET_WIDTHS;
$alt    ??= null;
$attrs  ??= '';    // attributs additionnels du <img> : 'uk-cover loading="lazy"'…

if (!$image instanceof \Kirby\Cms\File) return;

$sources  = \PixelOpen\KirbyUikitBuilder\Image::sources($image, $widths);
$alt    ??= $image->alt()->value() ?? '';
$sizesEsc = htmlspecialchars($sizes);
$dimAttrs = ($sources['width'] && $sources['height'])
    ? ' width="' . $sources['width'] . '" height="' . $sources['height'] . '"'
    : '';
?>
<picture>
  <?php if ($sources['webpSrcset']): ?>
  <source type="image/webp" srcset="<?= $sources['webpSrcset'] ?>"<?= $sources['srcset'] ? ' sizes="' . $sizesEsc . '"' : '' ?>>
  <?php endif ?>
  <img src="<?= $sources['src'] ?>"<?= $sources['srcset'] ? ' srcset="' . $sources['srcset'] . '" sizes="' . $sizesEsc . '"' : '' ?><?= $dimAttrs ?> alt="<?= htmlspecialchars($alt) ?>"<?= $attrs ? ' ' . $attrs : '' ?>>
</picture>
