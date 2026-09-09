# Snippet: ui/video

Shared video render helper. Used internally by `blocks/video` and callable from any page template.

## Parameters

| Variable | Type | Default | Description |
|----------|------|---------|-------------|
| `$source` | string | `'youtube'` | `'youtube'` \| `'vimeo'` \| `'file'` |
| `$youtube_url` | string | `''` | YouTube URL or video ID |
| `$vimeo_url` | string | `''` | Vimeo URL or video ID |
| `$video_file` | File\|null | `null` | Kirby File object for uploaded video |
| `$thumbnail` | File\|null | `null` | Kirby File object used as poster/overlay |
| `$title` | string | `'Video'` | Accessibility title for the iframe |
| `$ratio` | string | `'16:9'` | `'16:9'` \| `'4:3'` \| `'21:9'` \| `'1:1'` |
| `$nocookie` | bool | `true` | Use `youtube-nocookie.com` |
| `$click_to_play` | bool | `false` | Thumbnail overlay with play button |
| `$autoplay` | bool | `false` | Autoplay (file videos) |
| `$loop` | bool | `false` | Loop (file videos) |
| `$muted` | bool | `false` | Muted (forced true when autoplay is on) |
| `$uid` | string | auto | DOM ID prefix: auto-generated if not passed |

## Minimal usage

```php
// YouTube embed
snippet('ui/video', [
    'source'      => 'youtube',
    'youtube_url' => 'dQw4w9WgXcQ',
]);

// Vimeo with click-to-play and a thumbnail
snippet('ui/video', [
    'source'        => 'vimeo',
    'vimeo_url'     => '123456789',
    'thumbnail'     => $page->cover()->toFile(),
    'click_to_play' => true,
]);
```

## See also

- [`fields/video`](../fields/video.md): reusable page field
- [`blocks/video`](../blocks/video.md): block version
