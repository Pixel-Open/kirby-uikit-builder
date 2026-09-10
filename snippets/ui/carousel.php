<?php
$items         ??= null;   // Kirby\Cms\Structure, fields: item_image, item_title, item_text, item_link
$cols_mobile   ??= '1';
$cols_tablet   ??= '2';
$cols_desktop  ??= '3';
$cols_large    ??= null;
$ratio         ??= null;   // '16:9' | '4:3' | '3:2' | '1:1' | null
$gap           ??= '';     // '' | 'uk-grid-small' | 'uk-grid-medium' | 'uk-grid-large'
$center        ??= false;
$arrows        ??= true;
$arrows_outside ??= false;
$arrows_color  ??= 'dark'; // 'light' | 'dark'
$arrows_large  ??= false;
$dotnav        ??= false;
$dotnav_color  ??= 'dark';
$autoplay      ??= false;
$interval      ??= 5000;
$pause_hover   ??= true;
$finite        ??= false;

if (!$items || $items->isEmpty()) return;

$sliderOptions = implode('; ', array_filter([
    $center ? 'center: true' : '',
    'autoplay: ' . ($autoplay ? 'true' : 'false'),
    'autoplay-interval: ' . $interval,
    'pause-on-hover: ' . ($pause_hover ? 'true' : 'false'),
    'finite: ' . ($finite ? 'true' : 'false'),
]));

$itemsClass = trim(implode(' ', array_filter([
    'uk-slider-items uk-grid',
    $gap,
    'uk-child-width-1-' . $cols_mobile,
    'uk-child-width-1-' . $cols_tablet . '@s',
    'uk-child-width-1-' . $cols_desktop . '@m',
    $cols_large ? 'uk-child-width-1-' . $cols_large . '@l' : '',
])));

$ratioPaddings = ['16:9' => '56.25', '4:3' => '75', '3:2' => '66.67', '1:1' => '100'];
$ratioPad      = $ratio ? ($ratioPaddings[$ratio] ?? null) : null;

// srcset sizes derived from the column count per breakpoint (s: 640px, m: 960px)
$itemSizes = sprintf(
    '(min-width: 960px) %dvw, (min-width: 640px) %dvw, %dvw',
    ceil(100 / max(1, (int)$cols_desktop)),
    ceil(100 / max(1, (int)$cols_tablet)),
    ceil(100 / max(1, (int)$cols_mobile))
);

$colorClass     = $arrows_color === 'light' ? ' uk-light' : ' uk-dark';
$arrowSizeClass = $arrows_large ? ' uk-slidenav-large' : '';
$dotColorClass  = $dotnav_color === 'light' ? ' uk-light' : '';

if ($arrows_outside) {
    $arrowPrevClass = 'uk-position-center-left-out uk-hidden-hover' . $arrowSizeClass;
    $arrowNextClass = 'uk-position-center-right-out uk-hidden-hover' . $arrowSizeClass;
} else {
    $arrowPrevClass = 'uk-position-center-left uk-position-small uk-hidden-hover' . $arrowSizeClass;
    $arrowNextClass = 'uk-position-center-right uk-position-small uk-hidden-hover' . $arrowSizeClass;
}
?>
<div uk-slider="<?= $sliderOptions ?>">
  <div class="uk-position-relative uk-visible-toggle<?= $colorClass ?>" tabindex="-1">
    <div class="uk-slider-container">
      <ul class="<?= $itemsClass ?>">
      <?php foreach ($items as $i => $item):
        $image = $item->item_image()->toFiles()->first();
        $title = $item->item_title()->value();
        $text  = $item->item_text()->value();
        $link  = $item->item_link()->value();
      ?>
        <li>
          <?php if ($link): ?><a href="<?= htmlspecialchars($link) ?>"><?php endif ?>
          <?php if ($image): ?>
          <?php if ($ratioPad): ?><div class="uk-cover-container" style="padding-top: <?= $ratioPad ?>%;"><?php endif ?>
            <?php snippet('ui/picture', [
                'image' => $image,
                'sizes' => $itemSizes,
                'attrs' => ($ratioPad ? 'uk-cover ' : '') . 'loading="' . ($i === 0 ? 'eager' : 'lazy') . '"',
            ]) ?>
          <?php if ($ratioPad): ?></div><?php endif ?>
          <?php endif ?>
          <?php if ($title || $text): ?>
          <div class="uk-padding-small">
            <?php if ($title): ?><p class="uk-text-bold uk-margin-small-bottom"><?= htmlspecialchars($title) ?></p><?php endif ?>
            <?php if ($text): ?><p class="uk-text-small uk-margin-remove"><?= htmlspecialchars($text) ?></p><?php endif ?>
          </div>
          <?php endif ?>
          <?php if ($link): ?></a><?php endif ?>
        </li>
      <?php endforeach ?>
      </ul>
    </div>

    <?php if ($arrows): ?>
    <a class="<?= $arrowPrevClass ?>" href uk-slidenav-previous uk-slider-item="previous"></a>
    <a class="<?= $arrowNextClass ?>" href uk-slidenav-next uk-slider-item="next"></a>
    <?php endif ?>
  </div>

  <?php if ($dotnav): ?>
  <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin<?= $dotColorClass ?>"></ul>
  <?php endif ?>
</div>
