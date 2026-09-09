# Block `button`

UIkit button with style, size, and various link types.

## Fields

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | text | - | Button label |
| `link_type` | select | `internal` | Link type: `internal` · `anchor` · `file` · `email` · `telephone` |
| `link_page` | pages | - | Internal page (when `link_type = internal`) |
| `link_anchor` | text | - | Anchor ID e.g. `contact` (when `link_type = anchor`) |
| `link_file` | files | - | File (when `link_type = file`) |
| `link_email` | email | - | Email address (when `link_type = email`) |
| `link_phone` | tel | - | Phone number (when `link_type = telephone`) |
| `target` | toggle | `false` | Open in a new tab |
| `style` | select | `default` | UIkit style: `default` · `primary` · `secondary` · `text` · `link` · `danger` |
| `size` | select | `default` | Size: `small` · `default` · `large` |
| `margin_top` | select | `medium` | Top margin: none · small · medium · large |
