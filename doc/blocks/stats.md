# Block: stats

Grid of stat counters with optional `uk-countup` animation, icon, prefix, and suffix.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `items` | structure | List of stat items |
| `items[].value` | text | The number or stat (e.g. `1200`, `99.9`) |
| `items[].prefix` | text | Text before the value (e.g. `+`) |
| `items[].suffix` | text | Text after the value (e.g. `%`, `k`) |
| `items[].label` | text | Description below the value |
| `items[].icon` | text | UIkit icon name (optional) |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `cols_tablet` | `1-2` | Columns from 640px (1, 2 or 3) |
| `cols_desktop` | `1-4` | Columns from 960px (2, 3 or 4) |
| `style` | `""` | Box style: plain or card |
| `icon_color` | `""` | Icon color: primary, success, warning, danger, muted |
| `countup` | `true` | Animate numeric values with `uk-countup` |
| `alignment` | `uk-text-center` | Text alignment: left, center, right |

## Rendered HTML

```html
<!-- countup enabled, centered, card style -->
<div class="uk-grid uk-grid-match uk-child-width-1-2@s uk-child-width-1-4@m" uk-grid>
  <div>
    <div class="uk-card uk-card-default uk-card-body uk-text-center">
      <div class="uk-margin-small-bottom uk-text-primary">
        <span uk-icon="icon: users; ratio: 2"></span>
      </div>
      <div class="uk-heading-medium uk-margin-remove">
        <span>+</span>
        <span uk-countup="end: 1200">1200</span>
      </div>
      <p class="uk-text-muted uk-margin-small-top">Happy clients</p>
    </div>
  </div>
</div>
```

## Notes

- `uk-grid-match` is always applied so all items share the same height within a row
- `uk-countup` only activates when `countup` is enabled **and** the value is numeric
- Non-numeric values (e.g. `Free`, `∞`) are rendered as plain text regardless of the `countup` toggle
- `uk-countup` requires UIkit JS to be loaded
