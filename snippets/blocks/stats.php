<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsTablet  = $block->cols_tablet()->value() ?: '1-2';
$colsDesktop = $block->cols_desktop()->value() ?: '1-4';
$style       = $block->style()->value();
$iconColor   = $block->icon_color()->value();
$countup     = $block->countup()->isTrue();
$alignment   = $block->alignment()->value() ?: 'uk-text-center';

$gridClass = 'uk-grid uk-grid-match uk-child-width-' . $colsTablet . '@s uk-child-width-' . $colsDesktop . '@m';
$boxClass  = $style === 'card' ? 'uk-card uk-card-default uk-card-body' : '';
$iconColorClass = $iconColor ? 'uk-text-' . $iconColor : '';
?>
<div class="<?= $gridClass ?>" uk-grid>
  <?php foreach ($items as $item):
    $value  = $item->value()->value();
    $prefix = $item->prefix()->value();
    $suffix = $item->suffix()->value();
    $label  = $item->label()->value();
    $icon   = $item->icon()->value();

    $innerClass = implode(' ', array_filter([$boxClass, $alignment]));
  ?>
  <div>
    <div<?= $innerClass ? ' class="' . $innerClass . '"' : '' ?>>
      <?php if ($icon): ?>
      <div class="uk-margin-small-bottom<?= $iconColorClass ? ' ' . $iconColorClass : '' ?>">
        <span uk-icon="icon: <?= html($icon) ?>; ratio: 2"></span>
      </div>
      <?php endif ?>
      <div class="uk-heading-medium uk-margin-remove">
        <?php if ($prefix): ?><span><?= html($prefix) ?></span><?php endif ?>
        <?php if ($countup && is_numeric($value)): ?>
        <span uk-countup="end: <?= (float)$value ?>"><?= html($value) ?></span>
        <?php else: ?>
        <span><?= html($value) ?></span>
        <?php endif ?>
        <?php if ($suffix): ?><span><?= html($suffix) ?></span><?php endif ?>
      </div>
      <?php if ($label): ?><p class="uk-text-muted uk-margin-small-top"><?= html($label) ?></p><?php endif ?>
    </div>
  </div>
  <?php endforeach ?>
</div>
