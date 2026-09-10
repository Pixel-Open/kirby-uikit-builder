<?php
$items    = $block->items()->toStructure();
$multiple = $block->multiple()->isTrue() ? 'multiple: true' : '';
if ($items->isEmpty()) return;
?>
<ul uk-accordion<?= $multiple ? '="' . $multiple . '"' : '' ?>>
<?php foreach ($items as $i => $item): ?>
  <li<?= ($i === 0 && $block->first_open()->isTrue()) ? ' class="uk-open"' : '' ?>>
    <a class="uk-accordion-title" href="#"><?= $item->title() ?></a>
    <?php // content() is a StructureObject method: the field of the same name
        // is only reachable through ->content()->get('content'). ?>
    <div class="uk-accordion-content"><?= $item->content()->get('content') ?></div>
  </li>
<?php endforeach ?>
</ul>
