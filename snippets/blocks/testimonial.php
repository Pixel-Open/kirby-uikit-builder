<?php
$items = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsTablet  = $block->cols_tablet()->or('1-2')->value();
$colsDesktop = $block->cols_desktop()->or('1-3')->value();
$gap         = $block->gap()->value();
$style       = $block->style()->or('card')->value();
$showRating  = $block->show_rating()->isEmpty() || $block->show_rating()->isTrue();
$showAvatar  = $block->show_avatar()->isEmpty() || $block->show_avatar()->isTrue();

$gridClass = trim(implode(' ', array_filter([
    'uk-grid',
    'uk-grid-match',
    $gap,
    'uk-child-width-' . $colsTablet . '@s',
    'uk-child-width-' . $colsDesktop . '@m',
])));
?>
<div class="<?= $gridClass ?>" uk-grid>
<?php foreach ($items as $item):
  $quote   = $item->quote_text()->value();
  $author  = $item->author_name()->value();
  $role    = $item->author_role()->value();
  $avatar  = $showAvatar ? $item->author_avatar()->toFiles()->first() : null;
  $rating  = (int)$item->rating()->value();

  $wrapClass = match($style) {
      'card'    => 'uk-card uk-card-default uk-card-body uk-card-small',
      'primary' => 'uk-card uk-card-primary uk-card-body uk-card-small',
      default   => '',
  };
?>
  <div>
    <blockquote class="uk-margin-remove<?= $wrapClass ? ' ' . $wrapClass : '' ?>">
      <?php if ($showRating && $rating > 0): ?>
      <div class="uk-margin-small-bottom uk-text-warning">
        <?php for ($s = 1; $s <= 5; $s++): ?>
        <span uk-icon="icon: star; ratio: 0.8"<?= $s > $rating ? ' class="uk-text-muted"' : '' ?>></span>
        <?php endfor ?>
      </div>
      <?php endif ?>
      <?php if ($quote): ?>
      <p class="uk-margin-small"><?= nl2br(htmlspecialchars($quote)) ?></p>
      <?php endif ?>
      <?php if ($author || $avatar): ?>
      <footer class="uk-flex uk-flex-middle uk-margin-small-top">
        <?php if ($avatar): ?>
        <img class="uk-border-circle uk-margin-small-right" src="<?= $avatar->crop(60, 60)->url() ?>" width="60" height="60" alt="<?= htmlspecialchars($author) ?>">
        <?php endif ?>
        <div>
          <?php if ($author): ?><strong><?= htmlspecialchars($author) ?></strong><?php endif ?>
          <?php if ($role): ?><br><span class="uk-text-muted uk-text-small"><?= htmlspecialchars($role) ?></span><?php endif ?>
        </div>
      </footer>
      <?php endif ?>
    </blockquote>
  </div>
<?php endforeach ?>
</div>
