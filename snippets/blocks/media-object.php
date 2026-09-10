<?php
$image         = $block->media_image()->toFiles()->first();
$hasBlocks     = $block->content_blocks()->isNotEmpty();
if (!$image && !$hasBlocks) return;

$imagePosition = $block->image_position()->or('left')->value();
$imageWidth    = $block->image_width()->or('1-2')->value();
$valign        = $block->valign()->or('middle')->value();
$gap           = $block->gap()->or(' uk-grid-large')->value();
$imageRatio    = $block->image_ratio()->value();

$reversed = $imagePosition === 'right';

$ratioPaddings = ['16:9' => '56.25', '4:3' => '75', '3:2' => '66.67', '1:1' => '100'];
$ratioPad      = $imageRatio ? ($ratioPaddings[$imageRatio] ?? null) : null;

$imageColClass = 'uk-width-' . $imageWidth . '@m' . ($reversed ? ' uk-flex-last@m' : '');

// srcset sizes derived from the width fraction ('1-2' gives 50vw above 960px)
[$num, $den] = array_pad(explode('-', $imageWidth, 2), 2, 1);
$imageSizes  = sprintf('(min-width: 960px) %dvw, 100vw', ceil(100 * (int)$num / max(1, (int)$den)));
?>
<div class="uk-grid<?= $gap ?> uk-flex-<?= $valign ?>" uk-grid>
  <?php if ($image): ?>
  <div class="<?= $imageColClass ?>">
    <?php if ($ratioPad): ?>
    <div class="uk-cover-container" style="padding-top: <?= $ratioPad ?>%;">
    <?php endif ?>
      <?php snippet('ui/picture', [
          'image' => $image,
          'sizes' => $imageSizes,
          'attrs' => ($ratioPad ? 'uk-cover ' : '') . 'loading="lazy"',
      ]) ?>
    <?php if ($ratioPad): ?></div><?php endif ?>
  </div>
  <?php endif ?>
  <?php if ($hasBlocks): ?>
  <div class="uk-width-expand">
    <?= $block->content_blocks()->toBlocks() ?>
  </div>
  <?php endif ?>
</div>
