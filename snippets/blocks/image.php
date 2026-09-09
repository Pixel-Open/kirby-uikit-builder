<?php
$location = $block->location()->or('kirby')->value();
$alt      = $block->alt()->value() ?? '';
$caption  = $block->caption()->value();

$imageFile = null;
$src       = '';

if ($location === 'web') {
    $src = $block->src()->value();
} else {
    $imageFile = $block->image()->toFile();
}

$alignment = $block->alignment()->or('center')->value();
$imgWidth  = $block->img_width()->or('auto')->value();
$ratio     = $block->ratio()->value() ?: null;
$shadow    = $block->shadow()->value();
$lightbox  = $block->lightbox()->isTrue();
$link      = $lightbox ? '' : $block->link()->value();
$eager     = $block->eager()->isTrue();

$alignClass = match($alignment) {
    'left'  => 'uk-align-left@m',
    'right' => 'uk-align-right@m',
    default => 'uk-align-center',
};
$widthClass = ($imgWidth !== 'auto') ? 'uk-width-' . $imgWidth . '@m' : '';

$figClass = trim('uk-margin ' . $alignClass . ' ' . $widthClass);
?>
<figure class="<?= $figClass ?>">
  <?php snippet('ui/image', [
      'image'   => $imageFile,
      'src'     => $src,
      'alt'     => $alt,
      'ratio'   => $ratio,
      'shadow'  => $shadow,
      'lightbox'=> $lightbox,
      'link'    => $link,
      'eager'   => $eager,
      'caption' => $caption,
  ]) ?>
</figure>
