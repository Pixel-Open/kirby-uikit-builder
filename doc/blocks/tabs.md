# Block `tabs`

UIkit tabs (`uk-tab` + `uk-switcher`). Supports horizontal and vertical modes, with animation on tab change.

## Tabs tab

Each tab is a structure with the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `tab_title` | text | Tab title (displayed in the navigation bar) |
| `content_blocks` | blocks | Content: `heading` · `text` · `image` · `button` · `spacer` · `accordion` · `gallery` |

## Settings tab

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `vertical` | toggle | `false` | Vertical tabs on the left (`uk-tab-left`) in a grid |
| `alignment` | select | `left` | Horizontal alignment: `left` · `center` · `right` (hidden when `vertical` is active) |
| `animation` | select | `fade` | Animation: fade (`uk-animation-fade`) · none |

## Notes

- The connection between `uk-tab` and `uk-switcher` uses `$block->id()` as a unique identifier.
- In **vertical** mode, the block generates a `uk-grid` with tabs in `uk-width-auto` and content in `uk-width-expand`.
- In **horizontal** mode, the tabs themselves serve as navigation, no separate dotnav.
