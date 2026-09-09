<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$listType = $block->list_type()->value() ?: 'bullet';
$divider  = $block->divider()->isTrue();
$large    = $block->large()->isTrue();
$color    = $block->color()->value();

$tag = $listType === 'decimal' ? 'ol' : 'ul';

$classes = implode(' ', array_filter([
    'uk-list',
    'uk-list-' . $listType,
    $divider ? 'uk-list-divider' : '',
    $large   ? 'uk-list-large'   : '',
    $color   ? 'uk-text-' . $color : '',
]));
?>
<<?= $tag ?> class="<?= $classes ?>">
  <?php foreach ($items as $item): ?>
  <li><?= $item->text()->kirbytext() ?></li>
  <?php endforeach ?>
</<?= $tag ?>>
