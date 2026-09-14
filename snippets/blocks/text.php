<?php
if ($block->text()->isEmpty()) return;

$normalize = fn ($value) => $value === 'inherit' ? '' : $value;
$width     = $normalize($block->text_width()->value());

$classes = implode(' ', array_filter([
    $normalize($block->text_size()->value()),
    $normalize($block->text_color()->value()),
    $normalize($block->text_style()->value()),
    $normalize($block->text_align()->value()),
    $normalize($block->text_align_tablet()->value()),
    $normalize($block->text_align_mobile()->value()),
    $normalize($block->text_columns()->value()),
    $width ? 'uk-width-' . $width . '@m uk-margin-auto' : '',
]));

if (!$classes) {
    echo $block->text();
    return;
}
?>
<div class="<?= $classes ?>"><?= $block->text() ?></div>
