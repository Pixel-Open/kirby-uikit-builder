<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$style       = $block->style()->value();
$markerColor = $block->marker_color()->value() ?: 'primary';
$alternate   = $block->alternate()->isTrue();

$isCard       = $style === 'card';
$rootClass    = 'block-timeline' . ($alternate ? ' block-timeline--alternate' : '');
$markerClass  = 'timeline-marker timeline-marker--' . $markerColor;
$contentClass = 'timeline-content' . ($isCard ? ' uk-card uk-card-default uk-card-body' : '');
?>
<ul class="<?= $rootClass ?>">
  <?php foreach ($items as $item):
    $date  = $item->date()->value();
    $title = $item->title()->value();
    $icon  = $item->icon()->value();
  ?>
  <li class="timeline-item">
    <div class="<?= $markerClass ?>">
      <?php if ($icon): ?><span uk-icon="icon: <?= html($icon) ?>"></span><?php endif ?>
    </div>
    <div class="<?= $contentClass ?>">
      <?php if ($date): ?><span class="timeline-date uk-text-meta"><?= html($date) ?></span><?php endif ?>
      <?php if ($title): ?><h3 class="uk-h4 uk-margin-remove"><?= html($title) ?></h3><?php endif ?>
      <?php if ($item->text()->isNotEmpty()): ?>
      <div class="uk-margin-small-top"><?= $item->text() ?></div>
      <?php endif ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
