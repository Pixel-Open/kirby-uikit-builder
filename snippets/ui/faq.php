<?php
$items      ??= null;
$title      ??= '';
$multiple   ??= false;
$first_open ??= true;
$icon       ??= null;  // UIkit icon name shown before each question, e.g. 'question', 'info' | null

if (!$items || $items->isEmpty()) return;

$multipleAttr = $multiple ? 'true' : 'false';
?>
<div class="uk-faq" itemscope itemtype="https://schema.org/FAQPage">

  <?php if ($title): ?>
  <h2 class="uk-heading-small uk-margin-medium-bottom"><?= html($title) ?></h2>
  <?php endif ?>

  <ul uk-accordion="multiple: <?= $multipleAttr ?>">
    <?php foreach ($items as $i => $item): ?>
    <li<?= ($first_open && $i === 0) ? ' class="uk-open"' : '' ?> itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
      <a class="uk-accordion-title" href="#" itemprop="name">
        <?php if ($icon): ?><span uk-icon="icon: <?= htmlspecialchars($icon) ?>" class="uk-margin-small-right"></span><?php endif ?>
        <?= $item->question()->html() ?>
      </a>
      <div class="uk-accordion-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text"><?= $item->answer() ?></div>
      </div>
    </li>
    <?php endforeach ?>
  </ul>

</div>
