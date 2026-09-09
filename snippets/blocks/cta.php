<?php
$heading   = $block->heading()->value();
$subtext   = $block->subtext()->kirbytext();
$btn1Label = $block->btn1_label()->value();
$btn1Url   = $block->btn1_url()->value();
$btn1Style = $block->btn1_style()->value() ?: 'uk-button-primary';
$btn2Label = $block->btn2_label()->value();
$btn2Url   = $block->btn2_url()->value();
$btn2Style = $block->btn2_style()->value() ?: 'uk-button-default';
$style     = $block->style()->value();
$alignment = $block->alignment()->value();

$hasButtons = ($btn1Label && $btn1Url) || ($btn2Label && $btn2Url);
if (!$heading && !$subtext && !$hasButtons) return;

$outerClass = trim(implode(' ', array_filter([
    match($style) {
        'card'      => 'uk-card uk-card-default uk-card-body',
        'primary'   => 'uk-background-primary uk-padding uk-light',
        'secondary' => 'uk-background-secondary uk-padding uk-light',
        default     => '',
    },
    $alignment,
])));
?>
<div<?= $outerClass ? ' class="' . $outerClass . '"' : '' ?>>
  <?php if ($heading): ?><h2><?= html($heading) ?></h2><?php endif ?>
  <?php if ($subtext): ?><div class="uk-margin"><?= $subtext ?></div><?php endif ?>
  <?php if ($hasButtons): ?>
  <div class="uk-margin">
    <?php if ($btn1Label && $btn1Url): ?><a href="<?= html($btn1Url) ?>" class="uk-button <?= $btn1Style ?>"><?= html($btn1Label) ?></a><?php endif ?>
    <?php if ($btn2Label && $btn2Url): ?><a href="<?= html($btn2Url) ?>" class="uk-button <?= $btn2Style ?> uk-margin-small-left"><?= html($btn2Label) ?></a><?php endif ?>
  </div>
  <?php endif ?>
</div>
