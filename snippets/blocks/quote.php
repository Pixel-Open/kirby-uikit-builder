<?php
if ($block->text()->isEmpty()) return;

$style = $block->style()->value() ?: 'plain';

$classes = ['kb-blockquote'];
if ($style === 'large')  $classes[] = 'kb-blockquote--large';
if ($style === 'border') $classes[] = 'kb-blockquote--border';
if ($style === 'center') $classes[] = 'kb-blockquote--center uk-text-center';
?>
<blockquote class="<?= implode(' ', $classes) ?>">
  <?= $block->text() ?>
  <?php if ($block->citation()->isNotEmpty()): ?>
  <footer><?= $block->citation() ?></footer>
  <?php endif ?>
</blockquote>
