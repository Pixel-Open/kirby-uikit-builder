<?php
$items      = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsMobile  = $block->cols_mobile()->value() ?: '1-3';
$colsTablet  = $block->cols_tablet()->value() ?: '1-4';
$colsDesktop = $block->cols_desktop()->value() ?: '1-5';
$gap         = $block->gap()->value();
$height      = (int) ($block->height()->value() ?: 60);
$grayscale   = $block->grayscale()->isTrue();
$opacity     = (int) ($block->opacity()->value() ?: 100);

$imgStyle = trim(implode('; ', array_filter([
    'height: ' . $height . 'px',
    'width: auto',
    'max-width: 100%',
    'object-fit: contain',
    'display: block',
    $grayscale ? 'filter: grayscale(100%)' : '',
    $opacity < 100 ? 'opacity: ' . ($opacity / 100) : '',
])));

$gridClass = trim(implode(' ', array_filter([
    'uk-grid',
    'uk-flex-middle',
    'uk-child-width-' . $colsMobile,
    'uk-child-width-' . $colsTablet . '@s',
    'uk-child-width-' . $colsDesktop . '@m',
    $gap ? 'uk-grid-' . $gap : '',
])));
?>
<div class="<?= $gridClass ?>" uk-grid>
  <?php foreach ($items as $item):
    $logo = $item->logo()->toFiles()->first();
    if (!$logo) continue;
    $name = $item->name()->value();
    $url  = PixelOpen\KirbyUikitBuilder\Url::safe($item->url()->value());
  ?>
  <div class="uk-flex uk-flex-center uk-flex-middle">
    <?php if ($url): ?>
    <a href="<?= html($url) ?>" target="_blank" rel="noopener" title="<?= html($name ?: '') ?>">
      <img src="<?= $logo->url() ?>" alt="<?= html($name ?: $logo->alt()) ?>" style="<?= $imgStyle ?>" loading="lazy">
    </a>
    <?php else: ?>
    <img src="<?= $logo->url() ?>" alt="<?= html($name ?: $logo->alt()) ?>" style="<?= $imgStyle ?>" loading="lazy">
    <?php endif ?>
  </div>
  <?php endforeach ?>
</div>
