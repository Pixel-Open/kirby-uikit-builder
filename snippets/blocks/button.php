<?php
// Link resolved by the plugin's block method (see index.php).
// An anchor keeps UIkit's smooth scrolling.
$src = $block->linkHref();

if (!$src || $block->text()->isEmpty()) return;

$extra = str_starts_with($src, '#') ? 'uk-scroll ' : '';

$buttonSize = $block->button_size()->value();
$buttonSize = $buttonSize === 'default' ? '' : $buttonSize;
$marginTop  = $block->margin_top()->value();
$marginTop  = $marginTop === 'none' ? '' : $marginTop;
?>
<a class="uk-button<?= $block->button_style()->value() ?><?= $buttonSize ?><?= $marginTop ?>"
   href="<?= htmlspecialchars($src) ?>"
   <?= $block->target()->isTrue() ? 'target="_blank" rel="noopener"' : '' ?>
   <?= $extra ?>role="button"><?= $block->text()->html() ?></a>
