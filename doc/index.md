# pixelopen/kirby-uikit-builder

UIkit blocks and layout helpers for Kirby CMS 5.

## Contents

| Section | Description |
|---------|-------------|
| [Configuration](configuration.md) | `config.php` options to customize the plugin |
| [Sections](sections.md) | Layout section fields (background, padding, scrollspy…) |
| **Blocks** | |
| [image](blocks/image.md) | UIkit image with WebP, lazy load, lightbox |
| [quote](blocks/quote.md) | Blockquote with style options (plain, large, border, center) |
| [code](blocks/code.md) | Code block with filename header, copy button, and line numbers |
| [slider](blocks/slider.md) | UIkit slideshow (`uk-slideshow`) |
| [carousel](blocks/carousel.md) | Multi-column UIkit carousel (`uk-slider`) |
| [media-object](blocks/media-object.md) | Image + text side by side |
| [tabs](blocks/tabs.md) | UIkit tabs (`uk-tab` + `uk-switcher`) |
| [testimonial](blocks/testimonial.md) | Testimonial grid with star ratings |
| [gallery](blocks/gallery.md) | Image gallery with UIkit lightbox |
| [accordion](blocks/accordion.md) | UIkit accordion |
| [heading](blocks/heading.md) | Heading with size and decorative style |
| [card](blocks/card.md) | UIkit card with image, badge, link |
| [button](blocks/button.md) | Button with multiple link types |
| [spacer](blocks/spacer.md) | Vertical spacer |
| [faq](blocks/faq.md) | FAQ with schema.org `FAQPage` markup |
| [table](blocks/table.md) | UIkit table with header, styles and responsive overflow |
| [cta](blocks/cta.md) | Call to action with heading, text, and up to two buttons |
| [icon-box](blocks/icon-box.md) | Grid of icon boxes with UIkit icons |
| [alert](blocks/alert.md) | UIkit alert box (primary / success / warning / danger) |
| [list](blocks/list.md) | UIkit styled list (bullet, check, numbered…) |
| [stats](blocks/stats.md) | Stat counters grid with uk-countup animation |
| [pricing](blocks/pricing.md) | Pricing cards with features list and highlighted plan |
| [video](blocks/video.md) | YouTube, Vimeo or uploaded file with ratio and autoplay |
| [team](blocks/team.md) | Team member grid with photo, bio, and social links |
| [logo-grid](blocks/logo-grid.md) | Partner / client logo grid with grayscale and opacity |
| [timeline](blocks/timeline.md) | Vertical timeline of steps or milestones, optional alternate layout |
| **Snippets** | |
| [ui/image](snippets/ui-image.md) | Image render helper (WebP, uk-img, ratio, animate…) |
| [ui/picture](snippets/ui-picture.md) | Minimal responsive `<picture>` (srcset original + WebP) |
| [ui/video](snippets/ui-video.md) | Video render helper (YouTube, Vimeo, file: shared with fields/video) |
| [ui/faq](snippets/ui-faq.md) | FAQ render helper (accordion + schema.org) |
| [ui/gallery](snippets/ui-gallery.md) | Gallery grid with lightbox and masonry |
| [ui/slider](snippets/ui-slider.md) | Slideshow render helper (`uk-slideshow`) |
| [ui/carousel](snippets/ui-carousel.md) | Multi-column carousel render helper (`uk-slider`) |
| **Fields** | |
| [fields/layout](fields/layout.md) | Layout field with configurable sections |
| [fields/video](fields/video.md) | Reusable video field (YouTube, Vimeo, file) for page blueprints |
| [fields/cover](fields/cover.md) | Reusable cover image field |
| [fields/faq](fields/faq.md) | Reusable FAQ structure field |
| [fields/gallery](fields/gallery.md) | Reusable gallery files field |
| [fields/slider](fields/slider.md) | Reusable slider structure field |
| [fields/carousel](fields/carousel.md) | Reusable carousel structure field |

## Link resolution

| Method | Description |
|--------|-------------|
| `$block->linkHref(string $prefix = '')`<br>`$structureItem->linkHref(string $prefix = '')` | Resolves the composite link fields (`link_type` plus `link_page`, `link_url`, `link_anchor`, `link_file`, `link_email`, `link_phone`) into a URL, or `null` when the link is incomplete. The prefix lets one block carry several links: the `cta` block passes `btn1_` and `btn2_`. |

Anchors come back as `#id`, so a snippet can add `uk-scroll` with
`str_starts_with($href, '#')`. Used by the `button`, `card`, `cta` and `pricing` snippets.

## Quick start

1. Extend the layout field in a page blueprint:

```yaml
# site/blueprints/pages/default.yml
fields:
  layout:
    extends: fields/layout
```

2. Optionally, add a cover field:

```yaml
cover:
  extends: fields/cover
```

3. Optionally, customize via `config.php` (see [configuration](configuration.md)).
