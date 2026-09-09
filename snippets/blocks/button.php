<?php
// Lien résolu par le block method du plugin (voir index.php).
// Une ancre garde le défilement doux de UIkit.
$src = $block->linkHref();

if (!$src || $block->text()->isEmpty()) return;

$extra = str_starts_with($src, '#') ? 'uk-scroll ' : '';
?>
<a class="uk-button<?= $block->button_style()->value() ?><?= $block->button_size()->value() ?><?= $block->margin_top()->value() ?>"
   href="<?= htmlspecialchars($src) ?>"
   <?= $block->target()->isTrue() ? 'target="_blank" rel="noopener"' : '' ?>
   <?= $extra ?>role="button"><?= $block->text()->html() ?></a>
