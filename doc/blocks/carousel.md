# Block `carousel`

Multi-column UIkit carousel (`uk-slider`). Displays several items at once with arrow and/or dotnav navigation.

## Items tab

Each item is a structure with the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `item_image` | files | Image (max 1, page files) |
| `content_blocks` | blocks | Content below or above the image: `heading` · `text` · `button` |
| `item_link` | url | Clickable link on the item (optional) |

## Settings tab

### Columns

| Field | Default | Description |
|-------|---------|-------------|
| `cols_mobile` | `1` | Visible columns on mobile |
| `cols_tablet` | `2` | Visible columns on tablet (`@s`) |
| `cols_desktop` | `3` | Visible columns on desktop (`@m`) |
| `cols_large` | - | Columns on large screen (`@l`, optional) |

### Display

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `gap` | select | default | Gap between items: default · small · medium · large · none |
| `item_ratio` | select | - | Fixed image ratio with `uk-cover`: `16:9` · `4:3` · `3:2` · `1:1` |
| `center` | toggle | `false` | Center mode: active item is centered in the slider |

### Navigation: arrows

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `show_arrows` | toggle | `true` | Show arrows |
| `arrows_outside` | toggle | `false` | Arrows outside the slider (`uk-position-center-*-out`) |
| `arrows_color` | select | `dark` | Color: `light` · `dark` |
| `arrows_large` | toggle | `false` | Large arrows |

### Navigation: dotnav

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `show_dotnav` | toggle | `false` | Show dots below the slider |
| `dotnav_color` | select | `dark` | Color: `light` · `dark` |

## Behavior tab

| Field | Default | Description |
|-------|---------|-------------|
| `autoplay` | `false` | Auto-advance |
| `autoplay_interval` | `5000` | Interval in ms |
| `pause_on_hover` | `true` | Pause on hover |
| `finite` | `false` | No loop |
| `draggable` | `true` | Drag to navigate |

## Notes

- Uses `uk-slider` (not `uk-slideshow`): a distinct component; navigation attributes are `uk-slider-item`.
- The dotnav is positioned **outside** the clipping container (`uk-slider-container`), so it is visible below.
- `item_link` wraps the entire item in an `<a>`.
