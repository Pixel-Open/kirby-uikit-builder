# Block: text

Rich text paragraph override with visual size, color, style, per-breakpoint alignment, max width, and multi-column layout. Replaces the native Kirby `text` block.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `text` | writer | Paragraph text (no headings/lists nodes) |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `text_size` | `""` | Visual size: meta, small, large, lead |
| `text_color` | `""` | Color: muted, emphasis, primary, secondary, success, warning, danger |
| `text_style` | `""` | Italic, bold, or both |
| `text_align` | `""` | Alignment from the medium breakpoint (desktop) |
| `text_align_tablet` | `""` | Alignment from the small breakpoint (tablet) |
| `text_align_mobile` | `""` | Alignment below the small breakpoint (mobile) |
| `text_width` | `""` | Max width, centered: 1/2, 2/3, 3/4 |
| `text_columns` | `""` | Multi-column layout from the medium breakpoint: 2 or 3 columns |

## Rendered HTML

```html
<div class="uk-text-lead uk-text-primary uk-text-center@m uk-width-2-3@m uk-margin-auto">
  <p>Rich text…</p>
</div>
```

With every setting left on "inherit" no wrapper is added: the block renders exactly like the native Kirby `text` block, a bare `<p>…</p>`. The wrapping `<div>` only appears once at least one setting produces a class.

## Notes

- `text_columns` only kicks in from the medium breakpoint, mobile always stays single-column
- `text_width` also adds `uk-margin-auto` so the narrowed block stays centered in its container
