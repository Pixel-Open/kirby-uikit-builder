# Field: video

Reusable video field: embeds a complete video component (YouTube, Vimeo, or uploaded file) in any page blueprint via `extends: fields/video`.

## Usage in a blueprint

```yaml
# site/blueprints/pages/my-page.yml
fields:
  video:
    extends: fields/video
```

The field is a `type: structure` with `max: 1`, so it renders as a single editable row containing all video settings.

## Fields

| Field | Type | Condition | Description |
|-------|------|-----------|-------------|
| `source` | select | - | Source: YouTube, Vimeo, or uploaded file |
| `youtube_url` | text | source: youtube | Full URL or 11-char video ID |
| `vimeo_url` | text | source: vimeo | Full URL or numeric video ID |
| `video_file` | files | source: file | Uploaded video file |
| `thumbnail` | files | - | Preview image / poster |
| `video_title` | text | - | Accessibility title for the iframe |
| `ratio` | select | - | Aspect ratio: 16:9, 4:3, 21:9, 1:1 |
| `nocookie` | toggle | - | Use `youtube-nocookie.com` (default: true) |
| `click_to_play` | toggle | - | Thumbnail overlay, player loads on click |
| `autoplay` | toggle | source: file | Autoplay for file videos |
| `loop` | toggle | source: file | Loop for file videos |
| `muted` | toggle | source: file | Mute for file videos |

## Rendering in a template

```php
$video = $page->video()->toStructure()->first();

if ($video) {
    snippet('ui/video', [
        'source'        => $video->source()->value() ?: 'youtube',
        'youtube_url'   => $video->youtube_url()->value(),
        'vimeo_url'     => $video->vimeo_url()->value(),
        'video_file'    => $video->video_file()->toFile(),
        'thumbnail'     => $video->thumbnail()->toFile(),
        'title'         => $video->video_title()->value() ?: 'Video',
        'ratio'         => $video->ratio()->value() ?: '16:9',
        'nocookie'      => !$video->nocookie()->isFalse(),
        'click_to_play' => $video->click_to_play()->isTrue(),
        'autoplay'      => $video->autoplay()->isTrue(),
        'loop'          => $video->loop()->isTrue(),
        'muted'         => $video->muted()->isTrue(),
    ]);
}
```

## See also

- [`ui/video`](../snippets/ui-video.md): render snippet
- [`blocks/video`](../blocks/video.md): block version (uses this same snippet internally)
