# Block `media-object`

Image and text side by side: the classic "feature row". The image can be positioned left or right with responsive inversion.

## Content tab

| Field | Type | Description |
|-------|------|-------------|
| `media_image` | files | Image (max 1, page files) |
| `content_blocks` | blocks | Text content: `heading` · `text` · `button` · `spacer` |

## Settings tab

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `image_position` | select | `left` | Image position: `left` · `right` |
| `image_width` | select | `1/2` | Image column width: `1/3` · `1/2` · `2/3` |
| `valign` | select | `middle` | Vertical alignment: `top` · `middle` · `bottom` |
| `gap` | select | large | Gap between image and text: none · small · medium · large |
| `image_ratio` | select | - | Fixed image ratio with `uk-cover`: `16:9` · `4:3` · `3:2` · `1:1` |

## Notes

- On **mobile**, the image always stacks above the text (natural grid behavior).
- On **desktop**, when `image_position = right`, the image uses `uk-flex-last@m` to appear on the right without altering the HTML order.
- The text column takes `uk-width-expand` and fills the remaining space.
