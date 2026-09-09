# Block `faq`

Question/answer accordion with `FAQPage` schema.org markup for Google rich results.

## Fields

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | text | - | FAQ section title (optional) |
| `items` | structure | - | List of questions and answers |
| `items.question` | text | - | Question text (`itemprop="name"`) |
| `items.answer` | writer | - | Formatted answer (`itemprop="text"`) |
| `multiple` | toggle | `false` | Allow multiple items open at once |
| `first_open` | toggle | `true` | First item open by default |

## Notes

The `FAQPage` / `Question` / `Answer` microdata markup is generated automatically for Google indexing (rich results).
