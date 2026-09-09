# Block `image`

Override of the native Kirby image block. Adds automatic WebP, UIkit lazy load (`uk-img`), ratio, shadow, lightbox, and alignment.

Rendering is delegated to the [`ui/image`](../snippets/ui-image.md) snippet.

## Image tab

| Field | Type | Description |
|-------|------|-------------|
| `location` | radio | Source: `kirby` (page file) or `web` (external URL) |
| `image` | files | Image selected from page files (when `location = kirby`) |
| `src` | url | External image URL (when `location = web`) |
| `alt` | text | Alt text (falls back to `image->alt()`) |
| `caption` | writer | Caption below the image |

## Settings tab

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `alignment` | select | `center` | `left` · `center` · `right`: uses `uk-align-*` |
| `img_width` | select | `auto` | Width in layout: auto · 1/4 · 1/3 · 1/2 · 2/3 · full |
| `ratio` | select | - | Forced ratio with `uk-cover-container`: 16:9 · 4:3 · 3:2 · 1:1 · 21:9 · 3:1 |
| `shadow` | select | - | Shadow: `small` · `medium` · `large` · `xlarge` |
| `lightbox` | toggle | `false` | Opens image fullscreen on click (`uk-lightbox`) |
| `link` | url | - | Clickable URL (hidden when `lightbox` is active) |
| `eager` | toggle | `false` | High priority (`loading="eager" fetchpriority="high"`), enable for above-the-fold images |

## Notes

- **WebP**: generated automatically via `->thumb(['format' => 'webp'])` for Kirby files. Not available for external images.
- **Lazy load**: `uk-img` by default (load on scroll). Disabled when `eager = true`.
- **Alignment** `left`/`right`: uses `uk-align-left@m` / `uk-align-right@m` (float with text-wrap on desktop only).
- **Lightbox**: the caption becomes `data-caption` on the anchor attribute; it is not displayed below the image.
