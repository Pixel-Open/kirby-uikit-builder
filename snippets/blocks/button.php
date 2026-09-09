<?php
$src   = null;
$extra = '';

switch ($block->link_type()->value()) {
    case 'internal':
        if ($page = $block->link_page()->toPage()) {
            $src = $page->url();
        }
        break;
    case 'url':
        $src = $block->link_url()->value();
        break;
    case 'anchor':
        $src   = '#' . $block->link_anchor()->value();
        $extra = ' uk-scroll';
        break;
    case 'file':
        if ($file = $block->link_file()->toFile()) {
            $src = $file->url();
        }
        break;
    case 'email':
        $src = 'mailto:' . $block->link_email()->value();
        break;
    case 'telephone':
        $src = 'tel:' . $block->link_phone()->value();
        break;
}

if (!$src || $block->text()->isEmpty()) return;
?>
<a class="uk-button<?= $block->button_style()->value() ?><?= $block->button_size()->value() ?><?= $block->margin_top()->value() ?>"
   href="<?= htmlspecialchars($src) ?>"
   <?= $block->target()->isTrue() ? 'target="_blank" rel="noopener"' : '' ?>
   <?= $extra ?>role="button"><?= $block->text()->html() ?></a>
