<?php
$level     = $block->level()->or('h2');
$normalize = fn ($value) => $value === 'inherit' ? '' : $value;
$classes   = implode(' ', array_filter([
    $normalize($block->heading_style()->value()),
    $normalize($block->heading_size()->value()),
    $normalize($block->heading_color()->value()),
    $normalize($block->text_align()->value()),
    $normalize($block->text_align_tablet()->value()),
    $normalize($block->text_align_mobile()->value()),
]));
$isLine  = $block->heading_style()->value() === 'uk-heading-line';
if ($block->text()->isEmpty()) return;
?>
<<?= $level ?><?= $classes ? ' class="' . $classes . '"' : '' ?>><?= $isLine ? '<span>' . $block->text() . '</span>' : $block->text() ?></<?= $level ?>>
