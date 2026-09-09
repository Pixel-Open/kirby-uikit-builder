<?php
$items       ??= null;   // Kirby\Cms\Structure, fields: slide_image, slide_caption, slide_link
$ratio       ??= '16:9'; // '16:9' | '4:3' | '21:9' | '1:1' | '3:2' | '3:1'
$animation   ??= 'fade'; // 'fade' | 'slide' | 'scale' | 'pull' | 'push'
$autoplay    ??= false;
$interval    ??= 7000;
$pause_hover ??= true;
$finite      ??= false;
$arrows      ??= true;
$arrows_color ??= 'light';  // 'light' | 'dark'
$arrows_large ??= false;
$dotnav      ??= false;
$dotnav_color ??= 'light';

if (!$items || $items->isEmpty()) return;

$multipleSlides = $items->count() > 1;

$ssOptions = implode('; ', array_filter([
    'animation: ' . $animation,
    'ratio: ' . $ratio,
    'autoplay: ' . ($autoplay ? 'true' : 'false'),
    'autoplay-interval: ' . $interval,
    'pause-on-hover: ' . ($pause_hover ? 'true' : 'false'),
    'finite: ' . ($finite ? 'true' : 'false'),
]));

$colorClass      = $arrows_color === 'light' ? ' uk-light' : ' uk-dark';
$arrowSizeClass  = $arrows_large ? ' uk-slidenav-large' : '';
?>
<div class="uk-position-relative uk-visible-toggle<?= $colorClass ?>" tabindex="-1" uk-slideshow="<?= $ssOptions ?>">

  <ul class="uk-slideshow-items">
  <?php foreach ($items as $i => $slide):
    $image   = $slide->slide_image()->toFiles()->first();
    $caption = $slide->slide_caption()->value();
    $link    = $slide->slide_link()->value();
  ?>
    <li>
      <?php if ($link): ?><a href="<?= htmlspecialchars($link) ?>"><?php endif ?>
      <?php if ($image): ?>
      <?php snippet('ui/picture', [
          'image'  => $image,
          'widths' => \PixelOpen\KirbyUikitBuilder\Image::COVER_WIDTHS,
          'attrs'  => 'uk-cover loading="' . ($i === 0 ? 'eager' : 'lazy') . '"',
      ]) ?>
      <?php endif ?>
      <?php if ($caption): ?>
      <div class="uk-position-bottom-right uk-label uk-label-secondary uk-position-small">
        <?= htmlspecialchars($caption) ?>
      </div>
      <?php endif ?>
      <?php if ($link): ?></a><?php endif ?>
    </li>
  <?php endforeach ?>
  </ul>

  <?php if ($multipleSlides): ?>

  <?php if ($arrows): ?>
  <a class="uk-position-center-left uk-position-small uk-hidden-hover<?= $arrowSizeClass ?>" href uk-slidenav-previous uk-slideshow-item="previous"></a>
  <a class="uk-position-center-right uk-position-small uk-hidden-hover<?= $arrowSizeClass ?>" href uk-slidenav-next uk-slideshow-item="next"></a>
  <?php endif ?>

  <?php if ($dotnav): ?>
  <div class="uk-position-bottom-center uk-position-small">
    <ul class="uk-slideshow-nav uk-dotnav">
      <?php foreach ($items as $i => $slide): ?>
      <li uk-slideshow-item="<?= $i ?>"><a href><?= $i + 1 ?></a></li>
      <?php endforeach ?>
    </ul>
  </div>
  <?php endif ?>

  <?php endif ?>

</div>
