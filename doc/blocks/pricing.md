# Block: pricing

Pricing cards grid with features list, CTA button, badge, and highlighted plan support.

## Panel fields

### Content tab: items structure

| Field | Type | Description |
|-------|------|-------------|
| `name` | text | Plan name (e.g. Pro) |
| `price` | text | Price display (e.g. `29`, `Free`) |
| `period` | text | Period suffix (e.g. `/month`) |
| `description` | text | Short tagline |
| `features` | textarea | One feature per line: renders as checkmark list |
| `btn_label` | text | CTA button label |
| `link_type` | select | `internal` (default), `url`, `anchor`, `file`, `email`, `telephone` |
| `link_page` | pages | Target page, when `link_type: internal` |
| `link_url` | url | Absolute URL, when `link_type: url` |
| `link_anchor` | slug | Anchor id, when `link_type: anchor` |
| `link_file` | files | Target file, when `link_type: file` |
| `link_email` | email | Email address, when `link_type: email` |
| `link_phone` | tel | Phone number, when `link_type: telephone` |
| `highlighted` | toggle | Mark as featured (uses `highlighted_style` card color) |
| `badge` | text | Badge text (e.g. `Most popular`) |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `cols_tablet` | `1-2` | Columns from 640px |
| `cols_desktop` | `1-3` | Columns from 960px |
| `btn_style` | `uk-button-primary` | Default button style |
| `highlighted_style` | `primary` | Card color for highlighted plan (primary or secondary) |

## Rendered HTML

```html
<div class="uk-grid uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid>

  <!-- Standard plan -->
  <div>
    <div class="uk-card uk-card-default uk-card-body">
      <h3 class="uk-card-title">Starter</h3>
      <p class="uk-text-muted">For individuals</p>
      <div class="uk-heading-medium uk-margin-top">
        Free
      </div>
      <ul class="uk-list uk-list-check uk-margin">
        <li>Feature A</li>
        <li>Feature B</li>
      </ul>
      <div class="uk-margin-top">
        <a href="/signup" class="uk-button uk-button-primary uk-width-1-1">Get started</a>
      </div>
    </div>
  </div>

  <!-- Highlighted plan -->
  <div>
    <div class="uk-card uk-card-primary uk-light uk-card-body">
      <div class="uk-card-badge uk-label">Most popular</div>
      <h3 class="uk-card-title">Pro</h3>
      <p class="uk-text-muted">For teams</p>
      <div class="uk-heading-medium uk-margin-top">
        $29<span class="uk-text-small uk-text-muted">/month</span>
      </div>
      <ul class="uk-list uk-list-check uk-margin">
        <li>Everything in Starter</li>
        <li>Team features</li>
      </ul>
      <div class="uk-margin-top">
        <a href="/pro" class="uk-button uk-button-default uk-width-1-1">Get Pro</a>
      </div>
    </div>
  </div>

</div>
```

## Notes

- The highlighted card always uses `uk-button-default` for its button (ensures contrast on colored background)
- `uk-grid-match` ensures all cards are the same height within a row

## Migrating from 1.0.0

The per-plan `btn_url` field is gone: the same composite link fields as the
[button](button.md) block replace it, resolved by `$item->linkHref()`. A plain path such
as `/contact` was never valid in a `url` field, and one invalid row blocked every save of
the whole page. Re-link each plan in the Panel, or rewrite its stored JSON:

```diff
- "btn_url": "/contact",
+ "link_type": "internal",
+ "link_page": ["page://wljMYhm0m3e9QOwC"],
```
