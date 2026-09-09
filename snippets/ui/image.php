<?php
$image   ??= null;
$src     ??= '';
$alt     ??= '';
$ratio   ??= null;
$shadow  ??= '';
$lightbox ??= false;
$link    ??= '';
$eager   ??= false;
$caption ??= '';
$class   ??= '';
// $animate: UIkit animation class for scrollspy, e.g. 'uk-animation-fade' | null
$animate ??= null;
// $width: responsive width: string '1/2' or array ['default'=>'1/1','s'=>'1/2','m'=>'1/3'] | null
$width   ??= null;
// $sizes: attribut sizes du srcset, ex. '(min-width: 960px) 50vw, 100vw'
$sizes   ??= '100vw';

$imgSrc      = $src;
$srcset      = '';
$webpSrcset  = '';
$lightboxSrc = $src;
$dimW        = null;
$dimH        = null;

if ($image instanceof \Kirby\Cms\File) {
    if (!$alt) $alt = $image->alt()->value() ?? '';
    $sources     = \PixelOpen\KirbyUikitBuilder\Image::sources($image);
    $imgSrc      = $sources['src'];
    $srcset      = $sources['srcset'] ?? '';
    $webpSrcset  = $sources['webpSrcset'] ?? '';
    $lightboxSrc = $sources['full'];
    $dimW        = $sources['width'];
    $dimH        = $sources['height'];
}

if (!$imgSrc) return;

$ratioPaddings = [
    '16/9' => 56.25, '4/3' => 75,    '3/2'  => 66.67,
    '1/1'  => 100,   '21/9' => 42.86, '3/1'  => 33.33,
];
$ratioPad = $ratio ? ($ratioPaddings[$ratio] ?? null) : null;

$shadowClass  = $shadow ? 'uk-box-shadow-' . $shadow : '';
$altEsc       = htmlspecialchars($alt);
$srcAttr      = $eager ? 'src'    : 'data-src';
$srcsetAttr   = $eager ? 'srcset' : 'data-srcset';
$sizesAttr    = $eager ? 'sizes'  : 'data-sizes';
$sizesEsc     = htmlspecialchars($sizes);
$imgSrcsetAttrs = $srcset ? ' ' . $srcsetAttr . '="' . $srcset . '" ' . $sizesAttr . '="' . $sizesEsc . '"' : '';
$dimAttrs     = ($dimW && $dimH) ? ' width="' . $dimW . '" height="' . $dimH . '"' : '';
$ukImgAttr    = $eager ? '' : ' uk-img';
$loadingAttr  = $eager ? ' loading="eager" fetchpriority="high"' : '';
$coverAttr    = $ratioPad ? ' uk-cover' : '';
$imgClass     = ($shadowClass && !$ratioPad) ? ' class="' . $shadowClass . '"' : '';

$wrapperClass = trim(implode(' ', array_filter(['uk-cover-container', $shadowClass, $class])));
$lightboxHref = htmlspecialchars($lightboxSrc);
$lightboxCap  = ($lightbox && $caption) ? ' data-caption="' . htmlspecialchars(strip_tags($caption)) . '"' : '';

// Outer wrapper: width classes + scrollspy animation
$widthClasses = '';
if ($width !== null) {
    if (is_string($width)) {
        $widthClasses = 'uk-width-' . str_replace('/', '-', $width);
    } elseif (is_array($width)) {
        $parts = [];
        foreach ($width as $bp => $size) {
            $cls = 'uk-width-' . str_replace('/', '-', $size);
            if ($bp !== 'default') $cls .= '@' . $bp;
            $parts[] = $cls;
        }
        $widthClasses = implode(' ', $parts);
    }
}
$outerClass   = trim(implode(' ', array_filter([$widthClasses, $animate])));
$scrollspyAttr = $animate ? 'uk-scrollspy="cls: ' . htmlspecialchars($animate) . '"' : '';
$needsWrapper  = $widthClasses || $animate;
?>
<?php if ($needsWrapper): ?><div<?= $outerClass ? ' class="' . $outerClass . '"' : '' ?><?= $scrollspyAttr ? ' ' . $scrollspyAttr : '' ?>><?php endif ?>
<?php if ($lightbox): ?><a href="<?= $lightboxHref ?>"<?= $lightboxCap ?> uk-lightbox><?php endif ?>
<?php if ($link && !$lightbox): ?><a href="<?= htmlspecialchars($link) ?>"><?php endif ?>

<?php if ($ratioPad): ?><div class="<?= $wrapperClass ?>" style="padding-top: <?= $ratioPad ?>%;"><?php endif ?>

<?php if ($webpSrcset): ?>
<picture>
  <source type="image/webp" <?= $srcsetAttr ?>="<?= $webpSrcset ?>"<?= $srcset ? ' ' . $sizesAttr . '="' . $sizesEsc . '"' : '' ?>>
  <img <?= $srcAttr ?>="<?= $imgSrc ?>"<?= $imgSrcsetAttrs ?><?= $dimAttrs ?> alt="<?= $altEsc ?>"<?= $coverAttr ?><?= $ukImgAttr ?><?= $loadingAttr ?><?= $imgClass ?>>
</picture>
<?php else: ?>
<img <?= $srcAttr ?>="<?= $imgSrc ?>"<?= $imgSrcsetAttrs ?><?= $dimAttrs ?> alt="<?= $altEsc ?>"<?= $coverAttr ?><?= $ukImgAttr ?><?= $loadingAttr ?><?= $imgClass ?>>
<?php endif ?>

<?php if ($ratioPad): ?></div><?php endif ?>

<?php if ($lightbox): ?></a><?php endif ?>
<?php if ($link && !$lightbox): ?></a><?php endif ?>

<?php if ($caption && !$lightbox): ?>
<span class="uk-text-meta"><?= $caption ?></span>
<?php endif ?>
<?php if ($needsWrapper): ?></div><?php endif ?>
