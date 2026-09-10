<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsMobile    = $block->cols_mobile()->or('1')->value();
$colsTablet    = $block->cols_tablet()->or('2')->value();
$colsDesktop   = $block->cols_desktop()->or('3')->value();
$colsLarge     = $block->cols_large()->value();
$gap           = $block->gap()->value();
$itemRatio     = $block->item_ratio()->value();
$center        = $block->center()->isTrue();
$showArrows    = $block->show_arrows()->isEmpty() || $block->show_arrows()->isTrue();
$arrowsOutside = $block->arrows_outside()->isTrue();
$arrowsColor   = $block->arrows_color()->or('dark')->value();
$arrowsLarge   = $block->arrows_large()->isTrue();
$showDotnav    = $block->show_dotnav()->isTrue();
$dotnavColor   = $block->dotnav_color()->or('dark')->value();

$autoplay     = $block->autoplay()->isTrue();
$interval     = $block->autoplay_interval()->or(5000);
$pauseOnHover = $block->pause_on_hover()->isEmpty() || $block->pause_on_hover()->isTrue();
$finite       = $block->finite()->isTrue();
$draggable    = $block->draggable()->isEmpty() || $block->draggable()->isTrue();

$sliderOptions = implode('; ', array_filter([
    $center ? 'center: true' : '',
    'autoplay: ' . ($autoplay ? 'true' : 'false'),
    'autoplay-interval: ' . $interval,
    'pause-on-hover: ' . ($pauseOnHover ? 'true' : 'false'),
    'finite: ' . ($finite ? 'true' : 'false'),
    'draggable: ' . ($draggable ? 'true' : 'false'),
]));

$itemsClass = trim(implode(' ', array_filter([
    'uk-slider-items',
    'uk-grid',
    $gap,
    'uk-child-width-1-' . $colsMobile,
    'uk-child-width-1-' . $colsTablet . '@s',
    'uk-child-width-1-' . $colsDesktop . '@m',
    $colsLarge ? 'uk-child-width-1-' . $colsLarge . '@l' : '',
])));

$ratioPaddings = ['16:9' => '56.25', '4:3' => '75', '3:2' => '66.67', '1:1' => '100'];
$ratioPad      = $itemRatio ? ($ratioPaddings[$itemRatio] ?? null) : null;

// srcset sizes derived from the column count per breakpoint (s: 640px, m: 960px)
$itemSizes = sprintf(
    '(min-width: 960px) %dvw, (min-width: 640px) %dvw, %dvw',
    ceil(100 / max(1, (int)$colsDesktop)),
    ceil(100 / max(1, (int)$colsTablet)),
    ceil(100 / max(1, (int)$colsMobile))
);

$arrowsSizeClass = $arrowsLarge ? ' uk-slidenav-large' : '';
$colorClass      = $arrowsColor === 'light' ? ' uk-light' : ' uk-dark';

if ($arrowsOutside) {
    $arrowPrevClass = 'uk-position-center-left-out uk-hidden-hover' . $arrowsSizeClass;
    $arrowNextClass = 'uk-position-center-right-out uk-hidden-hover' . $arrowsSizeClass;
} else {
    $arrowPrevClass = 'uk-position-center-left uk-position-small uk-hidden-hover' . $arrowsSizeClass;
    $arrowNextClass = 'uk-position-center-right uk-position-small uk-hidden-hover' . $arrowsSizeClass;
}
?>
<div uk-slider="<?= $sliderOptions ?>">
  <div class="uk-position-relative uk-visible-toggle<?= $colorClass ?>" tabindex="-1">
    <div class="uk-slider-container">
      <ul class="<?= $itemsClass ?>">
      <?php foreach ($items as $i => $item):
        $image     = $item->item_image()->toFiles()->first();
        $hasBlocks = $item->content_blocks()->isNotEmpty();
        $link      = PixelOpen\KirbyUikitBuilder\Url::safe($item->item_link()->value());
      ?>
        <li>
          <?php if ($link): ?><a href="<?= htmlspecialchars($link) ?>"><?php endif ?>
          <?php if ($image): ?>
          <?php if ($ratioPad): ?>
          <div class="uk-cover-container" style="padding-top: <?= $ratioPad ?>%;">
          <?php endif ?>
            <?php snippet('ui/picture', [
                'image' => $image,
                'sizes' => $itemSizes,
                'attrs' => ($ratioPad ? 'uk-cover ' : '') . 'loading="' . ($i === 0 ? 'eager' : 'lazy') . '"',
            ]) ?>
          <?php if ($ratioPad): ?></div><?php endif ?>
          <?php endif ?>
          <?php if ($hasBlocks): ?>
          <div><?= $item->content_blocks()->toBlocks() ?></div>
          <?php endif ?>
          <?php if ($link): ?></a><?php endif ?>
        </li>
      <?php endforeach ?>
      </ul>
    </div>

    <?php if ($showArrows): ?>
    <a class="<?= $arrowPrevClass ?>" href uk-slidenav-previous uk-slider-item="previous"></a>
    <a class="<?= $arrowNextClass ?>" href uk-slidenav-next uk-slider-item="next"></a>
    <?php endif ?>
  </div>

  <?php if ($showDotnav): ?>
  <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
  <?php endif ?>
</div>
