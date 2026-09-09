<?php
$items    = $block->items()->toStructure();
$multiple = $block->multiple()->isTrue() ? 'multiple: true' : '';
if ($items->isEmpty()) return;
?>
<ul uk-accordion<?= $multiple ? '="' . $multiple . '"' : '' ?>>
<?php foreach ($items as $i => $item): ?>
  <li<?= ($i === 0 && $block->first_open()->isTrue()) ? ' class="uk-open"' : '' ?>>
    <a class="uk-accordion-title" href="#"><?= $item->title() ?></a>
    <div class="uk-accordion-content"><?= $item->content() ?></div>
  </li>
<?php endforeach ?>
</ul>
