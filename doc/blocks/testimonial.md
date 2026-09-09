# Block `testimonial`

Testimonial grid with quote, author, role, avatar, and star rating.

## Testimonials tab

Each testimonial is a structure with the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `quote_text` | textarea | Quote |
| `author_name` | text | Author name |
| `author_role` | text | Role or company |
| `author_avatar` | files | Photo (max 1, cropped to 60×60 px) |
| `rating` | select | Rating from 0 to 5 stars (0 = no rating) |

## Settings tab

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cols_tablet` | select | `2` | Columns on tablet (`@s`): 1 · 2 |
| `cols_desktop` | select | `3` | Columns on desktop (`@m`): 1 · 2 · 3 · 4 |
| `gap` | select | default | Gap between testimonials |
| `style` | select | `card` | Style: `plain` (bare) · `card` (UIkit card) · `primary` (primary card) |
| `show_rating` | toggle | `true` | Show stars |
| `show_avatar` | toggle | `true` | Show photo |

## Notes

- Stars use `uk-icon="star"`: empty stars are dimmed with `uk-text-muted`.
- `uk-grid-match` is enabled so all cards share the same height.
- The avatar is cropped to **60×60 px** with `uk-border-circle`.
