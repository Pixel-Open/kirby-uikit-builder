<?php extract(\PixelOpen\KirbyUikitBuilder\Section::prepare($layout)) ?>
<section
  class="<?= htmlspecialchars($classes) ?>"
  <?= $sectionId ? 'id="' . htmlspecialchars($sectionId) . '"' : '' ?>
  <?= $ariaLabel ? 'aria-label="' . htmlspecialchars($ariaLabel) . '"' : '' ?>
  <?= $sectionStyle ? 'style="' . htmlspecialchars($sectionStyle) . '"' : '' ?>
  <?php if ($bgImage && !$hasVideo): ?>
  data-src="<?= $bgImage->url() ?>"
  data-srcset="<?= $srcset ?>"
  data-sizes="100vw"
  <?= $eagerImage ? 'uk-img="loading: eager"' : 'uk-img' ?>
  <?php endif ?>
  <?= ($parallax && $bgImage && !$hasVideo) ? 'uk-parallax="bgy: ' . $parallaxSpeed . '"' : '' ?>
  <?= $scrollspyAttr ?>>
  <?php if ($bgVideoFile): ?>
  <video src="<?= $bgVideoFile->url() ?>" autoplay loop muted playsinline uk-cover></video>
  <?php elseif ($bgVideoUrl): ?>
  <iframe src="<?= htmlspecialchars($bgVideoUrl) ?>" width="1920" height="1080" frameborder="0" allowfullscreen uk-cover></iframe>
  <?php endif ?>
  <?php if ($overlayStyle): ?>
  <div class="uk-position-cover" style="<?= htmlspecialchars($overlayStyle) ?>"></div>
  <?php endif ?>
  <?php if ($shapeDivider && in_array($shapeDividerPos, ['top', 'both'])): ?>
  <div style="position:absolute;top:-1px;left:0;right:0;height:<?= htmlspecialchars($shapeDividerHeight) ?>;overflow:hidden;pointer-events:none;transform:scaleY(-1);">
    <?php snippet('layout/shape-divider', ['type' => $shapeDividerType, 'color' => $shapeDividerColor]) ?>
  </div>
  <?php endif ?>
  <?php if ($shapeDivider && in_array($shapeDividerPos, ['bottom', 'both'])): ?>
  <div style="position:absolute;bottom:-1px;left:0;right:0;height:<?= htmlspecialchars($shapeDividerHeight) ?>;overflow:hidden;pointer-events:none;">
    <?php snippet('layout/shape-divider', ['type' => $shapeDividerType, 'color' => $shapeDividerColor]) ?>
  </div>
  <?php endif ?>
  <div class="<?= $containerClass ?> uk-position-relative">
    <div class="<?= implode(' ', array_filter(['uk-grid', $gridGap, $gridValign, $gridHalign, $gridDivider ? 'uk-grid-divider' : null])) ?>" uk-grid>
      <?php snippet('layout/grid', ['layout' => $layout]) ?>
    </div>
  </div>
</section>
