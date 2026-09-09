<?php
$tabs = $block->tabs()->toStructure();
if ($tabs->isEmpty()) return;

$vertical  = $block->vertical()->isTrue();
$alignment = $block->alignment()->or('left')->value();
$animation = $block->animation()->or('uk-animation-fade')->value();

$tabId = 'tab-' . $block->id();

$ukTabOptions = implode('; ', array_filter([
    'connect: #' . $tabId,
    $animation ? 'animation: ' . $animation : '',
]));

if ($vertical):
?>
<div uk-grid>
  <div class="uk-width-auto">
    <ul class="uk-tab-left" uk-tab="<?= $ukTabOptions ?>">
      <?php foreach ($tabs as $i => $tab): ?>
      <li><a href><?= htmlspecialchars($tab->tab_title()->value()) ?></a></li>
      <?php endforeach ?>
    </ul>
  </div>
  <div class="uk-width-expand">
    <ul id="<?= $tabId ?>" class="uk-switcher">
      <?php foreach ($tabs as $tab): ?>
      <li><?= $tab->content_blocks()->toBlocks() ?></li>
      <?php endforeach ?>
    </ul>
  </div>
</div>
<?php else:
  $alignClass = match($alignment) {
      'center' => ' uk-flex-center',
      'right'  => ' uk-flex-right',
      default  => '',
  };
?>
<ul class="uk-tab<?= $alignClass ?>" uk-tab="<?= $ukTabOptions ?>">
  <?php foreach ($tabs as $tab): ?>
  <li><a href><?= htmlspecialchars($tab->tab_title()->value()) ?></a></li>
  <?php endforeach ?>
</ul>
<ul id="<?= $tabId ?>" class="uk-switcher uk-margin">
  <?php foreach ($tabs as $tab): ?>
  <li><?= $tab->content_blocks()->toBlocks() ?></li>
  <?php endforeach ?>
</ul>
<?php endif ?>
