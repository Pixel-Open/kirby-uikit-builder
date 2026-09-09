<?php
$images  = $block->gallery()->toFiles();
if ($images->isEmpty()) return;

$masonry = $block->gallery_style()->value() === 'masonry';
$widthClasses = [
    'two'   => 'uk-child-width-1-2@s',
    'three' => 'uk-child-width-1-2@s uk-child-width-1-3@m',
    'four'  => 'uk-child-width-1-2@s uk-child-width-1-4@m',
];
$widthClass = $widthClasses[$block->gallery_grid()->value()] ?? $widthClasses['three'];

$hoverStyle   = $block->hover_style()->value() ?: 'white';
$hoverOpacity = (int)($block->hover_opacity()->value() ?: 70) / 100;
$hoverIcon    = $block->hover_icon()->value();
$hoverCaption = $block->hover_caption()->isTrue();

$overlayClass = '';
$overlayStyle = '';
switch ($hoverStyle) {
    case 'white':
        $overlayStyle = 'background:rgba(255,255,255,' . $hoverOpacity . ')';
        break;
    case 'dark':
        $overlayStyle = 'background:rgba(0,0,0,' . $hoverOpacity . ')';
        $overlayClass = ' uk-light';
        break;
    case 'primary':
        $overlayClass = ' uk-overlay-primary';
        $overlayStyle = 'opacity:' . $hoverOpacity;
        break;
}
$showOverlay = $hoverStyle !== 'none' || $hoverIcon || $hoverCaption;
?>
<div class="<?= $widthClass ?> uk-grid-small" <?= $masonry ? 'uk-grid="masonry: true"' : 'uk-grid' ?> uk-lightbox="animation: fade">
<?php foreach ($images as $image):
    $caption = $image->caption()->isNotEmpty() ? $image->caption()->value() : ($image->alt()->value() ?? '');
    if ($masonry) {
        $thumb  = $image->thumb(['width' => 900, 'format' => 'webp'])->url();
        $src    = $image->resize(900)->url();
        $width  = $image->width();
        $height = $image->height();
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
        <img src="<?= $src ?>" alt="<?= htmlspecialchars($image->alt()->value() ?? '') ?>" width="<?= $width ?>" height="<?= $height ?>" loading="lazy">
      </picture>
      <?php if ($showOverlay): ?>
      <div class="uk-transition-fade uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-flex-column<?= $overlayClass ?>"<?= $overlayStyle ? ' style="' . $overlayStyle . '"' : '' ?>>
        <?php if ($hoverIcon): ?><span uk-icon="icon: <?= $hoverIcon ?>; ratio: 1.5"></span><?php endif ?>
        <?php if ($hoverCaption && $caption): ?><p class="uk-text-small uk-margin-small-top uk-margin-remove-bottom"><?= htmlspecialchars($caption) ?></p><?php endif ?>
      </div>
      <?php endif ?>
    </a>
  </div>
<?php endforeach ?>
</div>
