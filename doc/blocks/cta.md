# Block: cta

Call to action block with heading, text, up to two buttons, and optional background style.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `heading` | text | Main heading |
| `subtext` | writer | Supporting text (bold, italic, link) |
| `btn1_label` | text | First button label |
| `btn1_url` | url | First button URL |
| `btn1_style` | select | First button style (default/primary/secondary/danger/text/link) |
| `btn2_label` | text | Second button label (optional) |
| `btn2_url` | url | Second button URL (optional) |
| `btn2_style` | select | Second button style |

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
    <a href="/signup" class="uk-button uk-button-primary">Sign up free</a>
    <a href="/demo" class="uk-button uk-button-default uk-margin-small-left">Request a demo</a>
  </div>
</div>
```

## Style values

| Value | CSS applied |
|-------|-------------|
| `""` (plain) | No wrapper class |
| `card` | `uk-card uk-card-default uk-card-body` |
| `primary` | `uk-background-primary uk-padding uk-light` |
| `secondary` | `uk-background-secondary uk-padding uk-light` |
