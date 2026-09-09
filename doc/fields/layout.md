# Field `fields/layout`

Pre-configured Kirby layout field with UIkit sections, blocks, and settings tabs.

## Usage in a blueprint

```yaml
# site/blueprints/pages/default.yml
fields:
  layout:
    extends: fields/layout
```

## Available blocks

### Layout group
- `column-options`: column options (size, style, order…)

### Content group
- `heading`: heading with size and decorative style
- `text`: native Kirby text block
- `image`: UIkit image (WebP, lazy load, lightbox…)
- `quote`: native Kirby quote block
- `video`: native Kirby video block
- `button`: button with multiple link types
- `spacer`: vertical spacer

### Components group
- `card`: UIkit card
- `gallery`: image gallery with lightbox
- `accordion`: UIkit accordion
- `slider`: UIkit slideshow
- `carousel`: multi-column UIkit carousel
- `media-object`: image + text side by side
- `testimonial`: testimonial grid
- `tabs`: UIkit tabs
- `faq`: FAQ with schema.org markup

## Customization via config.php

```php
'pixelopen.kirby-uikit-builder' => [
    // Add blocks to an existing group
    'fieldsets' => [
        'content'    => ['cta'],
        'components' => ['map'],
    ],

    // Replace available layouts
    'layouts' => ['1/1', '1/2, 1/2', '1/3, 1/3, 1/3'],

    // Replace section backgrounds
    'backgrounds' => [
        ['value' => '', 'text' => 'None'],
        ['value' => 'uk-section-muted', 'text' => 'Grey'],
    ],
],
```

See [configuration](../configuration.md) for all options.

## Section tabs

Each layout section exposes 4 tabs:

| Tab | Content |
|-----|---------|
| **Background** | Background color, image, video, overlay |
| **Layout** | Padding, container, grid alignment, divider |
| **Effects** | Scrollspy, parallax, shape divider |
| **Advanced** | Responsive visibility, anchor ID, ARIA label, CSS classes |

See [sections](../sections.md) for details.
