<?php
$heading   = $block->heading()->value();
$subtext   = $block->subtext()->kirbytext();
$style     = $block->style()->value();
$alignment = $block->alignment()->value();

// Les deux boutons partagent le résolveur de lien du plugin (voir index.php),
// via les préfixes btn1_ et btn2_. Un bouton sans libellé ou sans cible saute.
$buttons = [];
foreach (['btn1' => 'uk-button-primary', 'btn2' => 'uk-button-default'] as $key => $defaultStyle) {
    $label = $block->content()->get($key . '_label')->value();
    $href  = $block->linkHref($key . '_');

    if (!$label || !$href) {
        continue;
    }

    $buttons[] = [
        'label'  => $label,
        'href'   => $href,
        'style'  => $block->content()->get($key . '_style')->value() ?: $defaultStyle,
        'target' => $block->content()->get($key . '_target')->isTrue(),
        'scroll' => str_starts_with($href, '#'),
    ];
}

if (!$heading && !$subtext && !$buttons) return;

$outerClass = trim(implode(' ', array_filter([
    match($style) {
        'card'      => 'uk-card uk-card-default uk-card-body',
        'primary'   => 'uk-background-primary uk-padding uk-light',
        'secondary' => 'uk-background-secondary uk-padding uk-light',
        default     => '',
    },
    $alignment,
])));
?>
<div<?= $outerClass ? ' class="' . $outerClass . '"' : '' ?>>
  <?php if ($heading): ?><h2><?= html($heading) ?></h2><?php endif ?>
  <?php if ($subtext): ?><div class="uk-margin"><?= $subtext ?></div><?php endif ?>
  <?php if ($buttons): ?>
  <div class="uk-margin">
    <?php foreach ($buttons as $i => $button): ?>
    <a href="<?= html($button['href']) ?>"
       class="uk-button <?= $button['style'] ?><?= $i > 0 ? ' uk-margin-small-left' : '' ?>"
       <?= $button['target'] ? 'target="_blank" rel="noopener"' : '' ?>
       <?= $button['scroll'] ? 'uk-scroll ' : '' ?>role="button"><?= html($button['label']) ?></a>
    <?php endforeach ?>
  </div>
  <?php endif ?>
</div>
