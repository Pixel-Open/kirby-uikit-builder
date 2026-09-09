# Block `slider`

Full-width UIkit slideshow (`uk-slideshow`). Supports arrows, dotnav, thumbnails, Ken Burns effect, and overlaid content on each slide.

## Slides tab

Each slide is a structure with the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `slide_image` | files | Background image (max 1, page files) |
| `content_blocks` | blocks | Overlaid content: `heading` · `text` · `button` |
| `slide_position` | select | Content position (overrides global): bottom · center · top with left/center/right variants |
| `slide_width` | select | Content block width: auto · 1/3 · 1/2 · 2/3 |

## Settings tab

### Display

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `animation` | select | `fade` | Transition animation: `slide` · `fade` · `scale` · `pull` · `push` |
| `kenburns` | toggle | `false` | Slow zoom effect on images |
| `size_mode` | toggle | `false` | Free height (viewport) instead of a fixed ratio |
| `ratio` | select | `16:9` | Format: `16:9` · `4:3` · `21:9` · `1:1` · `3:2` · `3:1` (hidden when `size_mode` is active) |
| `min_height` | number | - | Minimum height in px |
| `max_height` | number | - | Maximum height in px |

### Navigation: arrows

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `show_arrows` | toggle | `true` | Show previous/next arrows |
| `arrows_color` | select | `light` | Color: `light` (white) · `dark` (black) |
| `arrows_large` | toggle | `false` | Large arrows (`uk-slidenav-large`) |
| `arrows_position` | select | `center` | Vertical position: `center` · `top` · `bottom` |
| `arrows_offset` | select | `small` | Margin: `small` · `medium` · `large` · none |

### Navigation: secondary

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `navigation_type` | select | - | None · `dotnav` · `thumbnails` |
| `dotnav_color` | select | `light` | Dot color (when `dotnav`) |
| `dotnav_vertical` | toggle | `false` | Vertical dot layout (when `dotnav`) |

## Content tab

Global position and style of the overlaid content (applies to all slides unless overridden per slide).

| Field | Description |
|-------|-------------|
| `content_position` | Global content position |
| `content_padding` | Internal padding of the content block |
| `content_color` | Text color: `light` · `dark` |
| `content_style` | Overlay style: gradient · semi-transparent white · primary · none |
| `content_position_size` | Content margin from the edge |

## Behavior tab

| Field | Default | Description |
|-------|---------|-------------|
| `autoplay` | `false` | Auto-advance slides |
| `autoplay_interval` | `7000` | Interval in ms |
| `pause_on_hover` | `true` | Pause on hover |
| `finite` | `false` | No loop |
| `draggable` | `true` | Drag to navigate |

## Notes

- Navigation is only shown from **2 slides**.
- Arrows and dotnav/thumbnails are **independent**: they can be combined.
- Backward compat: if `navigation_type = arrows` in legacy content, `show_arrows` is implicitly enabled.
