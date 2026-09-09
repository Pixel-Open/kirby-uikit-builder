<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsTablet       = $block->cols_tablet()->value() ?: '1-2';
$colsDesktop      = $block->cols_desktop()->value() ?: '1-3';
$btnStyle         = $block->btn_style()->value() ?: 'uk-button-primary';
$highlightedStyle = $block->highlighted_style()->value() ?: 'primary';

$gridClass = 'uk-grid uk-grid-match uk-child-width-' . $colsTablet . '@s uk-child-width-' . $colsDesktop . '@m';
?>
<div class="<?= $gridClass ?>" uk-grid>
  <?php foreach ($items as $item):
    $highlighted = $item->highlighted()->isTrue();
    $badge       = $item->badge()->value();
    $name        = $item->name()->value();
    $price       = $item->price()->value();
    $period      = $item->period()->value();
    $description = $item->description()->value();
    $features    = array_filter(array_map('trim', explode("\n", $item->features()->value() ?? '')));
    $btnLabel    = $item->btn_label()->value();
    $btnUrl      = $item->linkHref();

    $cardClass = $highlighted
        ? 'uk-card uk-card-' . $highlightedStyle . ' uk-light uk-card-body'
        : 'uk-card uk-card-default uk-card-body';
    $cardBtnStyle = $highlighted ? 'uk-button-default' : $btnStyle;
  ?>
  <div>
    <div class="<?= $cardClass ?>">
      <?php if ($badge): ?><div class="uk-card-badge uk-label"><?= html($badge) ?></div><?php endif ?>
      <?php if ($name): ?><h3 class="uk-card-title"><?= html($name) ?></h3><?php endif ?>
      <?php if ($description): ?><p class="uk-text-muted"><?= html($description) ?></p><?php endif ?>
      <?php if ($price !== null && $price !== ''): ?>
      <div class="uk-heading-medium uk-margin-top">
        <?= html($price) ?><?php if ($period): ?><span class="uk-text-small uk-text-muted"><?= html($period) ?></span><?php endif ?>
      </div>
      <?php endif ?>
      <?php if ($features): ?>
      <ul class="uk-list uk-list-check uk-margin">
        <?php foreach ($features as $feature): ?>
        <li><?= html($feature) ?></li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
      <?php if ($btnLabel && $btnUrl): ?>
      <div class="uk-margin-top">
        <a href="<?= html($btnUrl) ?>" class="uk-button <?= $cardBtnStyle ?> uk-width-1-1"
           <?= str_starts_with($btnUrl, '#') ? 'uk-scroll ' : '' ?>role="button"><?= html($btnLabel) ?></a>
      </div>
      <?php endif ?>
    </div>
  </div>
  <?php endforeach ?>
</div>
