# Block: cta

Call to action block with heading, text, up to two buttons, and optional background style.

Each button uses the same composite link fields as the [button](button.md) block, so a
CTA can point to an internal page, an anchor, a file, an email address or a phone number.
Plain paths such as `/contact` are **not** valid in the `url` link type: Kirby's `url`
field requires an absolute URL, and an invalid value blocks every save of the whole page.
Use the `internal` link type for pages of the site.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `heading` | text | Main heading |
| `subtext` | writer | Supporting text (bold, italic, link) |
| `btn1_label` | text | First button label |
| `btn1_link_type` | select | `internal` (default), `url`, `anchor`, `file`, `email`, `telephone` |
| `btn1_target` | toggle | Open the first button in a new tab |
| `btn1_link_page` | pages | Target page, when `btn1_link_type: internal` |
| `btn1_link_url` | url | Absolute URL, when `btn1_link_type: url` |
| `btn1_link_anchor` | slug | Anchor id, when `btn1_link_type: anchor` |
| `btn1_link_file` | files | Target file, when `btn1_link_type: file` |
| `btn1_link_email` | email | Email address, when `btn1_link_type: email` |
| `btn1_link_phone` | tel | Phone number, when `btn1_link_type: telephone` |
| `btn1_style` | select | First button style (default/primary/secondary/danger/text/link) |
| `btn2_*` | | Same set of fields for the optional second button |

A button is rendered only when it has both a label and a resolvable link.

### Settings tab

| Field | Type | Description |
|-------|------|-------------|
| `style` | select | Background style: plain, card, primary, secondary |
| `alignment` | select | Text alignment: left, center (default), right |

## Rendered HTML

```html
<!-- style: card, alignment: center -->
<div class="uk-card uk-card-default uk-card-body uk-text-center">
  <h2>Ready to get started?</h2>
  <div class="uk-margin"><p>Join thousands of users today.</p></div>
  <div class="uk-margin">
    <a href="/signup" class="uk-button uk-button-primary" role="button">Sign up free</a>
    <a href="/demo" class="uk-button uk-button-default uk-margin-small-left" role="button">Request a demo</a>
  </div>
</div>
```

An `anchor` link also gets the `uk-scroll` attribute for smooth scrolling.

## Style values

| Value | CSS applied |
|-------|-------------|
| `""` (plain) | No wrapper class |
| `card` | `uk-card uk-card-default uk-card-body` |
| `primary` | `uk-background-primary uk-padding uk-light` |
| `secondary` | `uk-background-secondary uk-padding uk-light` |

## Migrating from 1.0.0

The `btn1_url` and `btn2_url` fields are gone. Existing content keeps its raw value in
the content file but the block no longer reads it, so each CTA has to be re-linked in the
Panel, or its stored JSON rewritten:

```diff
- "btn1_url": "/contact",
+ "btn1_link_type": "internal",
+ "btn1_link_page": ["page://wljMYhm0m3e9QOwC"],
```
