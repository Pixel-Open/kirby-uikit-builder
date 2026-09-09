# Block: alert

UIkit alert box (`uk-alert`) with type, optional title, content, closable toggle, and icon.

## Panel fields

| Field | Type | Description |
|-------|------|-------------|
| `alert_type` | select | Alert type: primary, success, warning, danger |
| `title` | text | Optional heading |
| `body` | writer | Alert content (bold, italic, link) |
| `closable` | toggle | Show a close button |
| `show_icon` | toggle | Show a type-matching icon in the title |

## Icon mapping

| Type | Icon |
|------|------|
| primary | `info` |
| success | `check` |
| warning | `warning` |
| danger | `ban` |

Icon is only rendered when `show_icon` is enabled **and** a `title` is set.

## Rendered HTML

```html
<!-- type: success, title set, closable, show_icon -->
<div class="uk-alert-success" uk-alert>
  <a class="uk-alert-close" uk-close></a>
  <h3>
    <span uk-icon="icon: check" class="uk-margin-small-right"></span>
    Operation successful
  </h3>
  <div><p>Your changes have been saved.</p></div>
</div>
```

```html
<!-- type: warning, no title, no icon -->
<div class="uk-alert-warning" uk-alert>
  <div><p>Please review before continuing.</p></div>
</div>
```

## UIkit reference

[getuikit.com/docs/alert](https://getuikit.com/docs/alert)
