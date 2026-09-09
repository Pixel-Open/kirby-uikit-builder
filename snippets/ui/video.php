<?php
// All variables have defaults: pass only what you need.
$source      ??= 'youtube';    // 'youtube' | 'vimeo' | 'file'
$youtube_url ??= '';
$vimeo_url   ??= '';
$video_file  ??= null;         // Kirby File object
$thumbnail   ??= null;         // Kirby File object
$title       ??= 'Video';
$ratio       ??= '16:9';
$nocookie    ??= true;
$click_to_play ??= false;
$autoplay    ??= false;
$loop        ??= false;
$muted       ??= false;
// Unique ID for DOM elements: auto-generated if not passed
$uid         ??= 'video-' . substr(md5(uniqid('', true)), 0, 8);

$aspectRatio = match($ratio) {
    '4:3'  => '4/3',
    '1:1'  => '1/1',
    '21:9' => '21/9',
    default => '16/9',
};

$iframeSrc = '';
$thumbUrl  = $thumbnail?->url();
$videoId   = '';

if ($source === 'youtube') {
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $m)) {
        $videoId = $m[1];
    } else {
        $videoId = trim($youtube_url);
    }
    if (!$videoId) return;
    $base      = $nocookie ? 'https://www.youtube-nocookie.com/embed/' : 'https://www.youtube.com/embed/';
    $iframeSrc = $base . $videoId . '?rel=0';
    if (!$thumbUrl && $click_to_play) {
        $thumbUrl = 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
    }

} elseif ($source === 'vimeo') {
    if (preg_match('/vimeo\.com\/(\d+)/', $vimeo_url, $m)) {
        $videoId = $m[1];
    } else {
        $videoId = trim($vimeo_url);
    }
    if (!$videoId) return;
    $iframeSrc = 'https://player.vimeo.com/video/' . $videoId . '?dnt=1';
}

$muted = $muted || $autoplay;
?>

<?php if ($click_to_play && $thumbUrl && in_array($source, ['youtube', 'vimeo'])): ?>
<div style="aspect-ratio: <?= $aspectRatio ?>; overflow: hidden; position: relative;">
  <div id="<?= $uid ?>-preview" style="position: absolute; inset: 0; cursor: pointer;">
    <img src="<?= $thumbUrl ?>" alt="<?= html($title) ?>"
         style="width: 100%; height: 100%; object-fit: cover; display: block;">
    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center;">
      <span uk-icon="icon: play-circle; ratio: 4" class="uk-text-white"></span>
    </div>
  </div>
  <div id="<?= $uid ?>-player" style="position: absolute; inset: 0; display: none;">
    <iframe src="<?= htmlspecialchars($iframeSrc) ?>&amp;autoplay=1" width="100%" height="100%" frameborder="0"
            allowfullscreen allow="autoplay; encrypted-media; picture-in-picture"
            title="<?= html($title) ?>"></iframe>
  </div>
  <button type="button"
    onclick="document.getElementById('<?= $uid ?>-preview').style.display='none'; document.getElementById('<?= $uid ?>-player').style.display='block'; this.remove();"
    style="position: absolute; inset: 0; background: transparent; border: none; cursor: pointer; width: 100%; height: 100%;"
    aria-label="<?= html($title) ?>"></button>
</div>

<?php elseif ($click_to_play && $thumbUrl && $source === 'file' && $video_file): ?>
<div style="aspect-ratio: <?= $aspectRatio ?>; overflow: hidden; position: relative;">
  <div id="<?= $uid ?>-preview" style="position: absolute; inset: 0; cursor: pointer;">
    <img src="<?= $thumbUrl ?>" alt="<?= html($title) ?>"
         style="width: 100%; height: 100%; object-fit: cover; display: block;">
    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center;">
      <span uk-icon="icon: play-circle; ratio: 4" class="uk-text-white"></span>
    </div>
  </div>
  <div id="<?= $uid ?>-player" style="position: absolute; inset: 0; display: none;">
    <video style="width: 100%; height: 100%; object-fit: cover;" controls
           <?= $autoplay ? 'autoplay' : '' ?> <?= $loop ? 'loop' : '' ?> <?= $muted ? 'muted' : '' ?> playsinline>
      <source src="<?= $video_file->url() ?>" type="<?= $video_file->mime() ?>">
    </video>
  </div>
  <button type="button"
    onclick="document.getElementById('<?= $uid ?>-preview').style.display='none'; var p=document.getElementById('<?= $uid ?>-player'); p.style.display='block'; <?= $autoplay ? 'p.querySelector(\'video\').play();' : '' ?> this.remove();"
    style="position: absolute; inset: 0; background: transparent; border: none; cursor: pointer; width: 100%; height: 100%;"
    aria-label="<?= html($title) ?>"></button>
</div>

<?php elseif ($source === 'file' && $video_file): ?>
<div style="aspect-ratio: <?= $aspectRatio ?>; overflow: hidden;">
  <video class="uk-width-1-1 uk-height-1-1" style="object-fit: cover;" controls
         <?= $autoplay ? 'autoplay' : '' ?> <?= $loop ? 'loop' : '' ?> <?= $muted ? 'muted' : '' ?>
         <?= $thumbUrl ? 'poster="' . $thumbUrl . '"' : '' ?> playsinline>
    <source src="<?= $video_file->url() ?>" type="<?= $video_file->mime() ?>">
  </video>
</div>

<?php elseif ($iframeSrc): ?>
<div style="aspect-ratio: <?= $aspectRatio ?>; overflow: hidden;">
  <iframe src="<?= htmlspecialchars($iframeSrc) ?>" width="100%" height="100%" frameborder="0"
          allowfullscreen allow="autoplay; encrypted-media; picture-in-picture"
          title="<?= html($title) ?>" loading="lazy"></iframe>
</div>
<?php endif ?>
