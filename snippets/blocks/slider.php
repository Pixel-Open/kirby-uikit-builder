<?php
$slides    = $block->slides()->toStructure();
if ($slides->isEmpty()) return;

$animation       = $block->animation()->or('fade')->value();
$kenburns        = $block->kenburns()->isTrue();
$navType         = $block->navigation_type()->value();
$contentStyle    = $block->content_style()->or('gradient')->value();
$posSize         = $block->content_position_size()->or(' uk-position-medium')->value();
$sizeMode        = $block->size_mode()->isTrue();
$ratio           = $block->ratio()->or('16:9')->value();
$minHeight       = (int)$block->min_height()->value();
$maxHeight       = (int)$block->max_height()->value();

// Content tab globals
$globalPos    = $block->content_position()->or('bottom')->value();
$contentPad   = $block->content_padding()->or('medium')->value();
$contentColor = $block->content_color()->or('light')->value();

// Behavior tab
$autoplay     = $block->autoplay()->isTrue();
$interval     = $block->autoplay_interval()->or(7000);
$pauseOnHover = $block->pause_on_hover()->isEmpty() || $block->pause_on_hover()->isTrue();
$finite       = $block->finite()->isTrue();
$draggable    = $block->draggable()->isEmpty() || $block->draggable()->isTrue();

// Navigation : arrows (indépendant) + nav secondaire (dotnav/thumbnails)
// Compat ascendante : ancien navigation_type=arrows → show_arrows implicite
$showArrows     = $block->show_arrows()->isEmpty() ? ($navType === 'arrows') : $block->show_arrows()->isTrue();
$arrowsColor    = $block->arrows_color()->or('light')->value();
$arrowsLarge    = $block->arrows_large()->isTrue();
$arrowsPosition = $block->arrows_position()->or('center')->value();
$arrowsOffset   = $block->arrows_offset()->or(' uk-position-small')->value();
$dotnavColor    = $block->dotnav_color()->or('light')->value();
$dotnavVertical = $block->dotnav_vertical()->isTrue();

$multipleSlides = $slides->count() > 1;
$kbOrigins      = ['uk-transform-origin-center-left', 'uk-transform-origin-top-right', 'uk-transform-origin-bottom-left', 'uk-transform-origin-top-center'];

$ssOptions = implode('; ', array_filter([
    'animation: ' . $animation,
    'ratio: ' . ($sizeMode ? 'false' : $ratio),
    $minHeight ? 'min-height: ' . $minHeight : '',
    $maxHeight ? 'max-height: ' . $maxHeight : '',
    'autoplay: ' . ($autoplay ? 'true' : 'false'),
    'autoplay-interval: ' . $interval,
    'pause-on-hover: ' . ($pauseOnHover ? 'true' : 'false'),
    'finite: ' . ($finite ? 'true' : 'false'),
    'draggable: ' . ($draggable ? 'true' : 'false'),
]));

$navColor   = ($navType === 'dotnav' && !$showArrows) ? $dotnavColor : $arrowsColor;
$colorClass = $contentColor === 'light' ? ' uk-light' : ' uk-dark';
?>
<div class="uk-position-relative uk-visible-toggle uk-<?= $navColor ?>" tabindex="-1" uk-slideshow="<?= $ssOptions ?>">

  <ul class="uk-slideshow-items">
  <?php foreach ($slides as $i => $slide):
    $image     = $slide->slide_image()->toFiles()->first();
    $hasBlocks = $slide->content_blocks()->isNotEmpty();
    $pos       = ($slide->slide_position()->isNotEmpty() ? $slide->slide_position()->value() : $globalPos);
    $width     = $slide->slide_width()->value();
    $isCenter  = $pos === 'center';
    $isFullWidth = in_array($pos, ['top', 'bottom']);

    $overlayClass = match ($contentStyle) {
        'default' => ' uk-overlay uk-overlay-default',
        'primary' => ' uk-overlay uk-overlay-primary',
        default   => '',
    };
  ?>
    <li>
      <?php if ($image): ?>
      <?php if ($kenburns): ?>
      <div class="uk-position-cover uk-animation-kenburns uk-animation-reverse <?= $kbOrigins[$i % count($kbOrigins)] ?>">
      <?php endif ?>
        <?php snippet('ui/picture', [
            'image'  => $image,
            'widths' => \PixelOpen\KirbyUikitBuilder\Image::COVER_WIDTHS,
            'attrs'  => 'uk-cover loading="' . ($i === 0 ? 'eager' : 'lazy') . '"',
        ]) ?>
      <?php if ($kenburns): ?></div><?php endif ?>
      <?php endif ?>

      <?php if ($hasBlocks): ?>
      <?php if ($contentStyle === 'gradient'): ?>
      <div class="uk-overlay-gradient uk-position-cover"></div>
      <?php endif ?>
      <div class="uk-position-<?= $pos . $posSize . $overlayClass . $colorClass ?>"<?= !$isCenter ? ' uk-slideshow-parallax="x: 100,-100"' : '' ?>>
        <?php if ($isFullWidth): ?><div class="uk-container"><?php endif ?>
        <div class="uk-padding-<?= $contentPad ?><?= $width ?>">
          <?= $slide->content_blocks()->toBlocks() ?>
        </div>
        <?php if ($isFullWidth): ?></div><?php endif ?>
      </div>
      <?php endif ?>

      <?php if ($image && $image->caption()->isNotEmpty()): ?>
      <div class="uk-position-bottom-right uk-label uk-label-secondary<?= $posSize ?>">
        <?= htmlspecialchars($image->caption()->value()) ?>
      </div>
      <?php endif ?>
    </li>
  <?php endforeach ?>
  </ul>

  <?php if ($multipleSlides): ?>

  <?php if ($navType === 'thumbnails'): ?>
  <div class="uk-position-bottom-center uk-position-small">
    <ul class="uk-slideshow-nav uk-thumbnav">
    <?php foreach ($slides as $i => $slide):
      $thumb = $slide->slide_image()->toFiles()->first();
      if (!$thumb) continue;
    ?>
      <li uk-slideshow-item="<?= $i ?>">
        <a href><img src="<?= $thumb->crop(150, 100)->url() ?>" width="150" height="100" alt=""></a>
      </li>
    <?php endforeach ?>
    </ul>
  </div>
  <?php elseif ($navType === 'dotnav'): ?>
  <div class="<?= $dotnavVertical ? 'uk-position-center-right uk-position-small' : 'uk-position-bottom-center uk-position-small' ?>">
    <ul class="uk-slideshow-nav uk-dotnav<?= $dotnavVertical ? ' uk-dotnav-vertical' : '' ?>">
      <?php foreach ($slides as $i => $slide): ?>
      <li uk-slideshow-item="<?= $i ?>"><a href><?= $i + 1 ?></a></li>
      <?php endforeach ?>
    </ul>
  </div>
  <?php endif ?>

  <?php if ($showArrows): ?>
  <?php $arrowsSizeClass = $arrowsLarge ? ' uk-slidenav-large' : '' ?>
  <a class="uk-position-<?= $arrowsPosition ?>-left<?= $arrowsOffset ?> uk-hidden-hover<?= $arrowsSizeClass ?>" href uk-slidenav-previous uk-slideshow-item="previous"></a>
  <a class="uk-position-<?= $arrowsPosition ?>-right<?= $arrowsOffset ?> uk-hidden-hover<?= $arrowsSizeClass ?>" href uk-slidenav-next uk-slideshow-item="next"></a>
  <?php endif ?>

  <?php endif ?>

</div>
