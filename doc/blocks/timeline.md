# Block: timeline

Vertical timeline of steps or milestones (process, company history) with date, title, rich text, and optional UIkit icon per step. No native UIkit component, styling relies on the `block-timeline` CSS classes (see project SCSS `components/_timeline.scss`).

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `items` | structure | List of steps |
| `items[].date` | text | Date or step label (e.g. `2024`, `Step 1`) |
| `items[].icon` | text | UIkit icon name shown in the marker (optional) |
| `items[].title` | text | Step title |
| `items[].text` | writer | Step description (rich text) |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `style` | `""` | Content style: plain or card |
| `marker_color` | `primary` | Marker color: primary, success, warning, danger, muted |
| `alternate` | `false` | Alternate steps left/right of a central line from 960px |

## Rendered HTML

```html
<!-- plain style, primary markers -->
<ul class="block-timeline">
  <li class="timeline-item">
    <div class="timeline-marker timeline-marker--primary">
      <span uk-icon="icon: check"></span>
    </div>
    <div class="timeline-content">
      <span class="timeline-date uk-text-meta">2024</span>
      <h3 class="uk-h4 uk-margin-remove">Company founded</h3>
      <div class="uk-margin-small-top"><p>Rich text…</p></div>
    </div>
  </li>
</ul>
```

With `alternate` enabled the root element gets the `block-timeline--alternate` modifier; with the card style, `timeline-content` also gets `uk-card uk-card-default uk-card-body`.

## Notes

- A marker without an icon renders a small centered dot (CSS `:empty` rule)
- The `alternate` layout only applies from the medium breakpoint (960px); below that the timeline falls back to the standard left-aligned layout
- The vertical line between items is pure CSS (`::before` pseudo-elements), no extra markup
