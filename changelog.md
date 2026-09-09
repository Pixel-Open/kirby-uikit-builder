# Changelog

## 1.0.1

+ New `linkHref(string $prefix = '')` method on blocks and structure rows: resolves a composite link to a URL.
- `cta` and `pricing`: button links moved to the composite link fields of the `button` block. The old `type: url` fields rejected a relative path like `/contact` and blocked page saving without naming the faulty block. Content still holding the old `btn1_url`, `btn2_url` or `btn_url` keeps rendering until the block is saved again from the Panel.
- Anchor links: `uk-scroll` was glued to `role="button"` and both attributes were lost. Smooth scrolling works again on `button`, `cta` and `pricing`.
- `icon-box`, `team` and `stats`: cards in a row now share the same height.

## 1.0.0 (09/09/2026)

+ First release