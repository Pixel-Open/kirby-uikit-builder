<?php
$files         ??= null;
$cols          ??= 'three';   // 'two' | 'three' | 'four'
$masonry       ??= false;
$hover_style   ??= 'dark';    // 'white' | 'dark' | 'primary' | 'none'
$hover_opacity ??= 70;        // 10-100
$hover_icon    ??= 'expand';  // UIkit icon name | ''
$hover_caption ??= false;
$gap           ??= 'small';   // '' | 'small' | 'medium'

if (!$files || $files->isEmpty()) return;

$widthClasses = [
    'two'   => 'uk-child-width-1-2@s',
    'three' => 'uk-child-width-1-2@s uk-child-width-1-3@m',
    'four'  => 'uk-child-width-1-2@s uk-child-width-1-4@m',
];
$widthClass = $widthClasses[$cols] ?? $widthClasses['three'];
$gapClass   = $gap ? 'uk-grid-' . $gap : '';

$opacityVal    = $hover_opacity / 100;
$overlayClass  = '';
$overlayStyle  = '';
switch ($hover_style) {
    case 'white':
        $overlayStyle = 'background:rgba(255,255,255,' . $opacityVal . ')';
        break;
    case 'dark':
        $overlayStyle = 'background:rgba(0,0,0,' . $opacityVal . ')';
        $overlayClass = ' uk-light';
        break;
    case 'primary':
        $overlayClass = ' uk-overlay-primary';
        $overlayStyle = 'opacity:' . $opacityVal;
        break;
}
$showOverlay = $hover_style !== 'none' || $hover_icon || $hover_caption;
?>
<div class="<?= $widthClass ?><?= $gapClass ? ' ' . $gapClass : '' ?>" <?= $masonry ? 'uk-grid="masonry: true"' : 'uk-grid' ?> uk-lightbox="animation: fade">
<?php foreach ($files as $image):
    $caption = $image->caption()->isNotEmpty() ? $image->caption()->value() : ($image->alt()->value() ?? '');
    if ($masonry) {
        // Masonry serves the uncropped image: its dimensions are those of the
        // generated variant, not of the original file. resize() already exposes
        // them, and does not upscale an image smaller than the breakpoint.
        $resized = $image->resize(900);
        $thumb   = $image->thumb(['width' => 900, 'format' => 'webp'])->url();
        $src     = $resized->url();
        $width   = $resized->width();
        $height  = $resized->height();
    } else {
        $thumb  = $image->thumb(['crop' => true, 'width' => 900, 'height' => 600, 'format' => 'webp'])->url();
        $src    = $image->crop(900, 600)->url();
        $width  = 900;
        $height = 600;
    }
?>
  <div>
    <a class="uk-inline-clip uk-transition-toggle" href="<?= $image->url() ?>" data-caption="<?= htmlspecialchars($caption) ?>">
      <picture>
        <source type="image/webp" srcset="<?= $thumb ?>">
        <?php // uk-width-1-1: an image smaller than its column would render at its
              // natural size and leave a gap, masonry being the only mode serving
              // uneven widths. The width/height attributes keep the ratio so the
              // space stays reserved. ?>
        <img class="uk-width-1-1" src="<?= $src ?>" alt="<?= htmlspecialchars($image->alt()->value() ?? '') ?>" width="<?= $width ?>" height="<?= $height ?>" loading="lazy">
      </picture>
      <?php if ($showOverlay): ?>
      <div class="uk-transition-fade uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-flex-column<?= $overlayClass ?>"<?= $overlayStyle ? ' style="' . $overlayStyle . '"' : '' ?>>
        <?php if ($hover_icon): ?><span uk-icon="icon: <?= $hover_icon ?>; ratio: 1.5"></span><?php endif ?>
        <?php if ($hover_caption && $caption): ?><p class="uk-text-small uk-margin-small-top uk-margin-remove-bottom"><?= htmlspecialchars($caption) ?></p><?php endif ?>
      </div>
      <?php endif ?>
    </a>
  </div>
<?php endforeach ?>
</div>
