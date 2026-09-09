# Block: list

UIkit styled list (`uk-list`) with configurable type, divider, size, and color.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `items` | structure | List items |
| `items[].text` | writer | Item content (bold, italic, link) |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `list_type` | `bullet` | List type: bullet, disc, decimal, hyphen, check |
| `divider` | false | Add dividers between items |
| `large` | false | Increase spacing between items |
| `color` | `""` | Text color: primary, success, warning, danger, muted |

## List types

| Value | UIkit class | Rendering |
|-------|-------------|-----------|
| `bullet` | `uk-list-bullet` | Custom bullet point |
| `disc` | `uk-list-disc` | Filled disc |
| `decimal` | `uk-list-decimal` | Numbered (renders as `<ol>`) |
| `hyphen` | `uk-list-hyphen` | Hyphen prefix |
| `check` | `uk-list-check` | Checkmark icon |

## Rendered HTML

```html
<!-- type: check, divider, primary color -->
<ul class="uk-list uk-list-check uk-list-divider uk-text-primary">
  <li><p>First item</p></li>
  <li><p>Second item</p></li>
</ul>
```

```html
<!-- type: decimal (numbered list) -->
<ol class="uk-list uk-list-decimal">
  <li><p>Step one</p></li>
  <li><p>Step two</p></li>
</ol>
```

## UIkit reference

[getuikit.com/docs/list](https://getuikit.com/docs/list)
