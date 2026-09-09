# Block: team

Grid of team member cards with photo, bio, and social links.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `items` | structure | Member list |
| `items[].photo` | files | Member photo (max 1, images only) |
| `items[].name` | text | Full name |
| `items[].role` | text | Role or position |
| `items[].bio` | writer | Short bio (bold, italic, link) |
| `items[].link_linkedin` | url | LinkedIn profile URL |
| `items[].link_twitter` | url | X / Twitter profile URL |
| `items[].link_github` | url | GitHub profile URL |
| `items[].link_instagram` | url | Instagram profile URL |
| `items[].link_email` | email | Email address |
| `items[].link_website` | url | Website URL |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `cols_tablet` | `1-2` | Columns on tablet (1-3) |
| `cols_desktop` | `1-4` | Columns on desktop (2-4) |
| `photo_ratio` | `square` | Photo crop: square, portrait (3:4), landscape (4:3) |
| `style` | plain | Card style: plain or card |
| `gap` | default | Gap between items |
| `show_bio` | `true` | Show bio text |
| `show_social` | `true` | Show social icon buttons |

## Notes

- `uk-grid-match` is always applied so all members share the same height within a row, whatever the length of their bio
- Photos are rendered as circles using `border-radius: 50%` and `aspect-ratio`
- When no photo is set, a fallback circle with the member's initial is shown
- Social links render as `uk-icon-button`: only links that are set are displayed
- UIkit icons used: `linkedin`, `x`, `github`, `instagram`, `mail`, `link`
