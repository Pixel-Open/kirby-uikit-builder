# Block: video

Responsive video block supporting YouTube, Vimeo, and uploaded files. Overrides the native Kirby `video` block.

## Panel fields

### Content tab

| Field | Type | Condition | Description |
|-------|------|-----------|-------------|
| `source` | select | - | Source type: YouTube, Vimeo, or uploaded file |
| `youtube_url` | text | source: youtube | Full URL or 11-character video ID |
| `vimeo_url` | text | source: vimeo | Full URL or numeric video ID |
| `video_file` | files | source: file | Uploaded video file |
| `thumbnail` | files | - | Preview image for all sources. Used as poster on file videos. Auto-fetched from YouTube if omitted. |
| `autoplay` | toggle | source: file | Start playback automatically |
| `loop` | toggle | source: file | Loop the video |
| `muted` | toggle | source: file | Mute audio (forced on when autoplay is enabled) |
| `video_title` | text | - | Accessibility title for the iframe |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `ratio` | `16:9` | Aspect ratio: 16:9, 4:3, 21:9, 1:1 |
| `nocookie` | `true` | Use `youtube-nocookie.com`: no cookies before user consent |
| `click_to_play` | `false` | Show thumbnail overlay; player only loads on click |

## Click-to-play behaviour

When `click_to_play` is enabled:

- A thumbnail is shown with a play icon overlay and a dark scrim
- Clicking loads the player and starts playback (`?autoplay=1` for iframes)
- The player replaces the thumbnail in-place (no lightbox)
- For YouTube: thumbnail is auto-fetched (`hqdefault.jpg`) if no image is set
- For Vimeo: requires a `thumbnail` image to be set; falls back to normal embed if missing
- For file: shows the thumbnail, reveals the `<video>` player on click

## GDPR / no-cookie

- **YouTube**: `nocookie: true` (default) switches the embed URL to `youtube-nocookie.com`. No cookies or tracking are set until the user interacts with the player.
- **Vimeo**: `?dnt=1` is always appended (do-not-track). No additional option needed.
- **click_to_play + nocookie**: optimal combination: the YouTube player doesn't even load until the user explicitly clicks.

## Rendered HTML (click-to-play, YouTube)

```html
<div style="aspect-ratio: 16/9; overflow: hidden; position: relative;">
  <div id="video-abc-preview" style="position: absolute; inset: 0; cursor: pointer;">
    <img src="https://img.youtube.com/vi/VIDEO_ID/hqdefault.jpg" alt="My video"
         style="width:100%;height:100%;object-fit:cover;display:block;">
    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35);
                display: flex; align-items: center; justify-content: center;">
      <span uk-icon="icon: play-circle; ratio: 4" class="uk-text-white"></span>
    </div>
  </div>
  <div id="video-abc-player" style="position: absolute; inset: 0; display: none;">
    <iframe src="https://www.youtube-nocookie.com/embed/VIDEO_ID?rel=0&enablejsapi=1&autoplay=1"
            width="100%" height="100%" frameborder="0" allowfullscreen
            allow="autoplay; encrypted-media; picture-in-picture"
            title="My video"></iframe>
  </div>
  <button type="button"
    onclick="document.getElementById('video-abc-preview').style.display='none';
             document.getElementById('video-abc-player').style.display='block'; this.remove();"
    style="position:absolute;inset:0;background:transparent;border:none;cursor:pointer;width:100%;height:100%;"
    aria-label="My video"></button>
</div>
```

## URL extraction

YouTube and Vimeo fields accept:
- Full watch URL: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
- Short URL: `https://youtu.be/dQw4w9WgXcQ`
- Embed URL: `https://www.youtube.com/embed/dQw4w9WgXcQ`
- Raw ID: `dQw4w9WgXcQ`
