# Block: logo-grid

Responsive grid of partner or client logos with configurable height, grayscale, and opacity.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `items` | structure | Logo list |
| `items[].logo` | files | Logo image (max 1) |
| `items[].name` | text | Name used as alt text |
| `items[].url` | url | Optional link URL |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `cols_mobile` | `1-3` | Columns on mobile (2 or 3) |
| `cols_tablet` | `1-4` | Columns on tablet (3-5) |
| `cols_desktop` | `1-5` | Columns on desktop (4-6) |
| `gap` | default | Gap between logos |
| `height` | `60` | Logo height in px (20-200) |
| `grayscale` | `false` | Apply grayscale CSS filter |
| `opacity` | `100` | Logo opacity in % (30-100) |

## Notes

- Logos are displayed with `object-fit: contain` to preserve aspect ratio
- Items without a logo file are skipped
- If a URL is set, the logo is wrapped in an `<a>` with `target="_blank" rel="noopener"`
- `grayscale` and `opacity` are applied as inline CSS `filter` / `opacity` styles
