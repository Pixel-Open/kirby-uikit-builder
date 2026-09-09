# Block: quote

Blockquote override with style options. Replaces the native Kirby `quote` block.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `text` | writer (inline) | Quote text |
| `citation` | writer (inline) | Attribution: author, source |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `style` | `plain` | Visual style (see below) |

## Styles

| Value | Effect |
|-------|--------|
| `plain` | Default browser/UIkit blockquote styling |
| `large` | Larger text (`font-size: 1.25em`) |
| `border` | Colored left border using `$global-primary-background` |
| `center` | `uk-text-center` alignment |
