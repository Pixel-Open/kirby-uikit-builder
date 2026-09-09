<?php
$cardColor  = $block->card_color()->value() ?: 'uk-card-default';
$isCustom   = $cardColor === 'custom';
$bgColor    = $isCustom ? $block->card_bg_color()->value() : '';
$textColor  = $isCustom ? $block->card_text_color()->value() : '';
$blockId    = htmlspecialchars($block->block_id()->value());
$blockClass = $block->block_class()->value();

$img      = $block->media_enable()->isTrue() ? $block->media_image()->toFiles()->first() : null;
$mediaPos = $block->media_position()->value() ?: 'top';

// Lien résolu par le block method du plugin (voir index.php). Un contenu écrit
// hors Panel peut n'avoir aucun link_type : on garde la retombée sur l'URL brute.
$href = null;
if ($block->link_enable()->isTrue()) {
    $href = $block->link_type()->isEmpty()
        ? ($block->link_url()->value() ?: null)
        : $block->linkHref();
}

$animation = '';
if ($block->animation()->isTrue()) {
    $cls       = $block->animation_type()->value() ?: 'uk-animation-fade';
    $delay     = (int)$block->animation_delay()->value();
    $animation = 'uk-scrollspy="cls: ' . htmlspecialchars($cls) . '; delay: ' . $delay . '"';
}

$cardClasses = implode(' ', array_filter([
    'uk-card',
    $isCustom ? null : $cardColor,
    $block->card_size()->value() ?: null,
    $block->card_hover()->isTrue() ? 'uk-card-hover' : null,
    !$img ? 'uk-card-body' : null,
    $textColor ?: null,
    $blockClass ?: null,
]));
$inlineStyle = $isCustom && $bgColor ? 'background-color:' . htmlspecialchars($bgColor) : '';
?>
<?php if ($href): ?><a href="<?= htmlspecialchars($href) ?>" class="uk-link-toggle"<?= $block->link_target()->isTrue() ? ' target="_blank" rel="noopener"' : '' ?>><?php endif ?>
<div <?= $blockId ? 'id="' . $blockId . '"' : '' ?> class="<?= htmlspecialchars($cardClasses) ?>"<?= $inlineStyle ? ' style="' . $inlineStyle . '"' : '' ?> <?= $animation ?>>
  <?php if ($block->card_badge()->isNotEmpty()): ?>
  <div class="uk-card-badge uk-label"><?= htmlspecialchars($block->card_badge()->value()) ?></div>
  <?php endif ?>
  <?php if ($img && $mediaPos === 'top'): ?>
  <div class="uk-card-media-top">
    <?php snippet('ui/picture', [
        'image' => $img,
        'sizes' => '(min-width: 640px) 50vw, 100vw',
        'attrs' => 'loading="lazy"',
    ]) ?>
  </div>
  <div class="uk-card-body"><?= $block->blocks()->toBlocks() ?></div>
  <?php elseif ($img && $mediaPos === 'bottom'): ?>
  <div class="uk-card-body"><?= $block->blocks()->toBlocks() ?></div>
  <div class="uk-card-media-bottom">
    <?php snippet('ui/picture', [
        'image' => $img,
        'sizes' => '(min-width: 640px) 50vw, 100vw',
        'attrs' => 'loading="lazy"',
    ]) ?>
  </div>
  <?php else: ?>
  <?= $block->blocks()->toBlocks() ?>
  <?php endif ?>
</div>
<?php if ($href): ?></a><?php endif ?>
