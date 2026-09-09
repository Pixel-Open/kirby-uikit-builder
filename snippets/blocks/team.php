<?php
$items       = $block->items()->toStructure();
if ($items->isEmpty()) return;

$colsTablet  = $block->cols_tablet()->value() ?: '1-2';
$colsDesktop = $block->cols_desktop()->value() ?: '1-4';
$photoRatio  = $block->photo_ratio()->value() ?: 'square';
$style       = $block->style()->value();
$gap         = $block->gap()->value();
$showBio     = $block->show_bio()->isTrue();
$showSocial  = $block->show_social()->isTrue();

$ratioMap = ['square' => '1/1', 'portrait' => '3/4', 'landscape' => '4/3'];
$aspectRatio = $ratioMap[$photoRatio] ?? '1/1';

$gridClass = trim(implode(' ', array_filter([
    'uk-grid',
    'uk-child-width-' . $colsTablet . '@s',
    'uk-child-width-' . $colsDesktop . '@m',
    $gap ? 'uk-grid-' . $gap : '',
])));

$isCard = $style === 'card';
?>
<div class="<?= $gridClass ?>" uk-grid>
  <?php foreach ($items as $item):
    $photo      = $item->photo()->toFiles()->first();
    $name       = $item->name()->value();
    $role       = $item->role()->value();
    $bio        = $item->bio()->value();
    $linkedin   = $item->link_linkedin()->value();
    $twitter    = $item->link_twitter()->value();
    $github     = $item->link_github()->value();
    $instagram  = $item->link_instagram()->value();
    $email      = $item->link_email()->value();
    $website    = $item->link_website()->value();

    $hasSocial  = $showSocial && ($linkedin || $twitter || $github || $instagram || $email || $website);
  ?>
  <div>
    <div class="<?= $isCard ? 'uk-card uk-card-default uk-card-body uk-text-center' : 'uk-text-center' ?>">
      <?php if ($photo): ?>
      <div class="uk-margin-bottom" style="aspect-ratio: <?= $aspectRatio ?>; overflow: hidden;<?= $photoRatio === 'square' ? ' border-radius: 50%;' : '' ?>">
        <img
          src="<?= $photo->url() ?>"
          alt="<?= html($name ?: $photo->alt()) ?>"
          style="width: 100%; height: 100%; object-fit: cover; display: block;"
          loading="lazy"
        >
      </div>
      <?php else: ?>
      <div class="uk-margin-bottom uk-flex uk-flex-center uk-flex-middle uk-background-muted" style="aspect-ratio: 1/1; overflow: hidden; border-radius: 50%;">
        <span class="uk-text-large uk-text-muted"><?= mb_strtoupper(mb_substr($name ?: '?', 0, 1)) ?></span>
      </div>
      <?php endif ?>

      <?php if ($name): ?><h3 class="uk-card-title uk-margin-remove"><?= html($name) ?></h3><?php endif ?>
      <?php if ($role): ?><p class="uk-text-muted uk-margin-small-top uk-margin-remove-bottom"><?= html($role) ?></p><?php endif ?>

      <?php if ($showBio && $bio): ?>
      <div class="uk-margin-small-top"><?= $bio ?></div>
      <?php endif ?>

      <?php if ($hasSocial): ?>
      <div class="uk-flex uk-flex-center uk-grid-small uk-margin-small-top" uk-grid>
        <?php if ($linkedin): ?>
        <div><a href="<?= html($linkedin) ?>" target="_blank" rel="noopener" class="uk-icon-button" aria-label="LinkedIn" uk-icon="linkedin"></a></div>
        <?php endif ?>
        <?php if ($twitter): ?>
        <div><a href="<?= html($twitter) ?>" target="_blank" rel="noopener" class="uk-icon-button" aria-label="X / Twitter" uk-icon="x"></a></div>
        <?php endif ?>
        <?php if ($github): ?>
        <div><a href="<?= html($github) ?>" target="_blank" rel="noopener" class="uk-icon-button" aria-label="GitHub" uk-icon="github"></a></div>
        <?php endif ?>
        <?php if ($instagram): ?>
        <div><a href="<?= html($instagram) ?>" target="_blank" rel="noopener" class="uk-icon-button" aria-label="Instagram" uk-icon="instagram"></a></div>
        <?php endif ?>
        <?php if ($email): ?>
        <div><a href="mailto:<?= html($email) ?>" class="uk-icon-button" aria-label="Email" uk-icon="mail"></a></div>
        <?php endif ?>
        <?php if ($website): ?>
        <div><a href="<?= html($website) ?>" target="_blank" rel="noopener" class="uk-icon-button" aria-label="Website" uk-icon="link"></a></div>
        <?php endif ?>
      </div>
      <?php endif ?>
    </div>
  </div>
  <?php endforeach ?>
</div>
