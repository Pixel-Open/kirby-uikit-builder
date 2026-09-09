# Block: icon-box

Grid of icon boxes, each with a UIkit icon, title, text, and optional link.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `items` | structure | List of icon boxes |
| `items[].icon` | text | UIkit icon name (e.g. `star`, `heart`, `home`) |
| `items[].title` | text | Box title |
| `items[].text` | writer | Box text (bold, italic, link) |
| `items[].link` | url | Optional link (wraps entire box) |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `cols_tablet` | `1-2` | Columns from 640px (1 or 2) |
| `cols_desktop` | `1-3` | Columns from 960px (2, 3, or 4) |
| `icon_size` | `2` | UIkit icon ratio (1-5) |
| `icon_color` | `""` | Icon color: primary, success, warning, danger, muted |
| `icon_position` | `top` | Icon position: top (stacked) or left (inline) |
| `style` | `""` | Box style: plain or card |
| `gap` | `""` | Grid gap: collapse, small, medium, large |

## Rendered HTML

```html
<!-- 3 cols desktop, icon top, card style, primary color -->
<div class="uk-grid uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid>
  <div>
    <div class="uk-card uk-card-default uk-card-body">
      <div class="uk-text-primary">
        <span uk-icon="icon: star; ratio: 2"></span>
      </div>
      <h3 class="uk-margin-small-top">Feature</h3>
      <div><p>Description text here.</p></div>
    </div>
  </div>
  …
</div>
```

With `icon_position: left`:
```html
<div class="uk-flex uk-flex-middle">
  <div class="uk-flex-none uk-margin-right">
    <span uk-icon="icon: star; ratio: 2" class="uk-text-primary"></span>
  </div>
  <div>
    <h3 class="uk-margin-remove-bottom">Feature</h3>
    <div><p>Description.</p></div>
  </div>
</div>
```

## Notes

- `uk-grid-match` is always applied so all boxes share the same height within a row, whatever the length of their text
- When a `link` is set, the wrapping `<a>` carries `uk-grid-item-match` so the card inside it stretches too

## UIkit icon names

Full list at [getuikit.com/docs/icon](https://getuikit.com/docs/icon). Common examples: `star`, `heart`, `check`, `mail`, `phone`, `home`, `user`, `lock`, `cloud`, `bolt`, `info`, `warning`.
