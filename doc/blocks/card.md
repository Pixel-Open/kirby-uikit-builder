# Block `card`

UIkit card with free content, optional image, badge, and link.

## Content tab

| Field | Type | Description |
|-------|------|-------------|
| `blocks` | blocks | Card content: `heading` · `text` · `button` · `spacer` |
| `color` | select | Card style: default · primary · secondary |
| `bg_color` | color | Custom background color |
| `badge` | text | Badge text (top-right corner) |

## Media tab

| Field | Type | Description |
|-------|------|-------------|
| `media_enable` | toggle | Enable image |
| `media_image` | files | Image (max 1, when `media_enable` is active) |
| `media_position` | select | Image position: `top` · `bottom` |

## Link tab

| Field | Type | Description |
|-------|------|-------------|
| `link_enable` | toggle | Make the entire card clickable |
| `link_url` | url | Destination URL |
| `link_target` | toggle | Open in a new tab |
