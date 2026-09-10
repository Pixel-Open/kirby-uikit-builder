# Changelog

## 1.0.2 (09/10/2026)

+ New `Url::safe()`, `Color` and `Css::length()`: validate the URLs, colours and lengths typed in the Panel before they reach an attribute. Escaping protects the attribute, never the value inside it.
+ Test suite and demo fixtures: PHPUnit next to the smoke test, unit tests for `src/`, render, escaping and snapshot tests over every block, and a generator that turns the fixtures into browsable demo pages. Not shipped, `tests/` stays export-ignored.
- `accordion`: the body of each panel never rendered and the block raised a fatal error. `content()` is a method of Kirby's structure rows, so the field of the same name has to go through `->content()->get('content')`.
- Shape dividers were cut to the ratio of their viewBox instead of being stretched to the requested height, and section content ran underneath them.
- `gallery` in masonry mode: `width` and `height` described the source file rather than the variant served, and an image smaller than its column left a gap beside it.
- `button`, `card`, `carousel`, `cta`, `icon-box`, `image`, `logo-grid`, `pricing` and `team`: a `javascript:` URL typed into a link field reached the `href` untouched.
- `card` background colour, shape divider height and column heights reached `style` unvalidated, so a value like `#f00;background-image:url(...)` added a second declaration.
- `card`, `tabs` and layout columns passed null to `htmlspecialchars()` and `trim()`, deprecated since PHP 8.1. Only content written outside the Panel is affected, the Panel always stores an empty string.

## 1.0.1 (09/09/2026)

+ New `linkHref(string $prefix = '')` method on blocks and structure rows: resolves a composite link to a URL.
- `cta` and `pricing`: button links moved to the composite link fields of the `button` block. The old `type: url` fields rejected a relative path like `/contact` and blocked page saving without naming the faulty block. Content still holding the old `btn1_url`, `btn2_url` or `btn_url` keeps rendering until the block is saved again from the Panel.
- Anchor links: `uk-scroll` was glued to `role="button"` and both attributes were lost. Smooth scrolling works again on `button`, `cta` and `pricing`.
- `icon-box`, `team` and `stats`: cards in a row now share the same height.

## 1.0.0 (09/09/2026)

+ First release
