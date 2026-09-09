# Block `table`

UIkit table (`uk-table`) with configurable header columns, style modifiers, and responsive overflow.

## Data tab

### Caption

| Field | Type | Description |
|-------|------|-------------|
| `caption` | text | Optional table caption (rendered as `<caption>`) |

### Columns (header)

Each column defines a `<th>` in the `<thead>` row.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `label` | text | - | Column heading text |
| `align` | select | left | Text alignment: left · center · right |
| `shrink` | toggle | `false` | `uk-table-shrink`: column takes minimum width (useful for icons, checkboxes) |

### Rows

Each row is a pipe-separated string of cell values matching the column order.

| Field | Type | Description |
|-------|------|-------------|
| `cells` | text | Cell values separated by `\|`: e.g. `Alice \| 32 \| Paris` |

Cell values support plain text. HTML is escaped.

## Settings tab

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style` | multiselect | - | Style modifiers (combinable): `divider` · `striped` · `hover` · `small` · `large` · `justify` |
| `responsive` | toggle | `true` | Wraps the table in `uk-overflow-auto` for horizontal scroll on mobile |

## Style modifiers

| Option | UIkit class | Effect |
|--------|-------------|--------|
| Row dividers | `uk-table-divider` | Horizontal lines between rows |
| Striped rows | `uk-table-striped` | Alternating row background |
| Hover highlight | `uk-table-hover` | Row highlighted on hover |
| Condensed | `uk-table-small` | Reduced cell padding |
| Spacious | `uk-table-large` | Increased cell padding |
| Justified | `uk-table-justify` | Removes padding from first and last column |

## Notes

- The `<thead>` is only rendered when at least one column is defined.
- Cell count per row should match the number of columns; extra cells are rendered without a matching header.
- `uk-table-shrink` and `uk-table-expand` (via `class`) can be applied per column.
