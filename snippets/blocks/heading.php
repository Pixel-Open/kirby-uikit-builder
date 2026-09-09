<?php
$level   = $block->level()->or('h2');
$classes = implode(' ', array_filter([
    $block->heading_style()->value(),
    $block->heading_size()->value(),
    $block->heading_color()->value(),
    $block->text_align()->value(),
    $block->text_align_tablet()->value(),
    $block->text_align_mobile()->value(),
]));
$isLine  = $block->heading_style()->value() === 'uk-heading-line';
if ($block->text()->isEmpty()) return;
?>
<<?= $level ?><?= $classes ? ' class="' . $classes . '"' : '' ?>><?= $isLine ? '<span>' . $block->text() . '</span>' : $block->text() ?></<?= $level ?>>
