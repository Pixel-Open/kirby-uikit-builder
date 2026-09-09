<?php
$alertType = $block->alert_type()->value() ?: 'primary';
$title     = $block->title()->value();
$body      = $block->body()->kirbytext();
$closable  = $block->closable()->isTrue();
$showIcon  = $block->show_icon()->isTrue();

if (!$title && !$body) return;

$iconMap = ['primary' => 'info', 'success' => 'check', 'warning' => 'warning', 'danger' => 'ban'];
$icon    = $showIcon ? ($iconMap[$alertType] ?? 'info') : null;
?>
<div class="uk-alert-<?= $alertType ?>" uk-alert>
  <?php if ($closable): ?><a class="uk-alert-close" uk-close></a><?php endif ?>
  <?php if ($title): ?>
  <h3><?php if ($icon): ?><span uk-icon="icon: <?= $icon ?>" class="uk-margin-small-right"></span><?php endif ?><?= html($title) ?></h3>
  <?php endif ?>
  <?php if ($body): ?><div><?= $body ?></div><?php endif ?>
</div>
