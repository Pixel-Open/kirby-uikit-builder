<?php
$items      = $block->items()->toStructure();
$multiple   = $block->multiple()->isTrue() ? 'true' : 'false';
$first_open = $block->first_open()->isTrue();

if ($items->isEmpty()) return;
?>
<div class="block-faq uk-margin-large" itemscope itemtype="https://schema.org/FAQPage">

  <?php if ($block->title()->isNotEmpty()): ?>
  <h2 class="uk-heading-small uk-margin-medium-bottom"><?= $block->title()->html() ?></h2>
  <?php endif ?>

  <ul uk-accordion="multiple: <?= $multiple ?>">
    <?php foreach ($items as $i => $item): ?>
    <li<?= ($first_open && $i === 0) ? ' class="uk-open"' : '' ?> itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
      <a class="uk-accordion-title" href="#" itemprop="name"><?= $item->question()->html() ?></a>
      <div class="uk-accordion-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text"><?= $item->answer() ?></div>
      </div>
    </li>
    <?php endforeach ?>
  </ul>

</div>
