<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsTablet  = $block->cols_tablet()->value() ?: '1-2';
$colsDesktop = $block->cols_desktop()->value() ?: '1-3';
$iconSize    = $block->icon_size()->value() ?: '2';
$iconColor   = $block->icon_color()->value();
$position    = $block->icon_position()->value() ?: 'top';
$style       = $block->style()->value();
$gap         = $block->gap()->value();

$gridClasses = implode(' ', array_filter([
    'uk-grid',
    'uk-child-width-' . $colsTablet . '@s',
    'uk-child-width-' . $colsDesktop . '@m',
    $gap ? 'uk-grid-' . $gap : '',
]));

$boxClass       = $style === 'card' ? 'uk-card uk-card-default uk-card-body' : '';
$iconColorClass = $iconColor ? 'uk-text-' . $iconColor : '';
?>
<div class="<?= $gridClasses ?>" uk-grid>
  <?php foreach ($items as $item):
    $icon  = $item->icon()->value();
    $title = $item->title()->value();
    $text  = $item->text()->kirbytext();
    $link  = $item->link()->value();
  ?>
  <div>
    <?php if ($link): ?><a href="<?= html($link) ?>" class="uk-link-reset"><?php endif ?>
    <div<?= $boxClass ? ' class="' . $boxClass . '"' : '' ?>>
      <?php if ($position === 'left' && $icon): ?>
      <div class="uk-flex uk-flex-middle">
        <div class="uk-flex-none uk-margin-right">
          <span uk-icon="icon: <?= html($icon) ?>; ratio: <?= $iconSize ?>"<?= $iconColorClass ? ' class="' . $iconColorClass . '"' : '' ?>></span>
        </div>
        <div>
          <?php if ($title): ?><h3 class="uk-margin-remove-bottom"><?= html($title) ?></h3><?php endif ?>
          <?php if ($text): ?><div><?= $text ?></div><?php endif ?>
        </div>
      </div>
      <?php else: ?>
      <?php if ($icon): ?><div<?= $iconColorClass ? ' class="' . $iconColorClass . '"' : '' ?>><span uk-icon="icon: <?= html($icon) ?>; ratio: <?= $iconSize ?>"></span></div><?php endif ?>
      <?php if ($title): ?><h3 class="uk-margin-small-top"><?= html($title) ?></h3><?php endif ?>
      <?php if ($text): ?><div><?= $text ?></div><?php endif ?>
      <?php endif ?>
    </div>
    <?php if ($link): ?></a><?php endif ?>
  </div>
  <?php endforeach ?>
</div>
