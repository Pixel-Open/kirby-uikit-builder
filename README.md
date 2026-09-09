# pixelopen/kirby-uikit-builder

A UIkit 3 page builder for Kirby CMS 5: a layout field with full section control,
26 content blocks, and a responsive image pipeline. Everything renders as plain
UIkit markup, so the output stays themeable with UIkit variables alone.

## Requirements

- PHP 8.2 to 8.5
- Kirby 5.0 or newer
- UIkit 3 loaded by your theme (the plugin ships no CSS or JS of its own on the
  front end; it only styles the Panel)

## Installation

```bash
composer require pixelopen/kirby-uikit-builder
```

Or drop the repository into `site/plugins/kirby-uikit-builder/`.

## Quick start

Extend the layout field in a page blueprint:

```yaml
# site/blueprints/pages/default.yml
fields:
  layout:
    extends: fields/layout
```

Render it in the matching template:

```php
<?php foreach ($page->layout()->toLayouts() as $layout): ?>
  <?php snippet('layout/section', ['layout' => $layout]) ?>
<?php endforeach ?>
```

That is the whole integration. `layout/section` takes care of the `<section>`
wrapper, the background, the container, the grid and the columns, and dispatches
each block to its own snippet.

## What you get

### Layout field

A Kirby layout field whose section settings are spread over four Panel tabs:

| Tab | Controls |
|-----|----------|
| Background | color, custom color or gradient, image, video (file or URL), overlay with opacity and gradient, text color |
| Layout | padding, padding removal, container width, grid gap, alignment, dividers, shape dividers (10 shapes, top/bottom/both) |
| Effects | parallax, eager loading, scrollspy animation with delay and repeat |
| Advanced | responsive visibility, anchor ID, ARIA label, extra CSS classes |

See [`doc/sections.md`](doc/sections.md) for the full field reference.

### Blocks

26 blocks, grouped in the Panel's block picker:

| Group | Blocks |
|-------|--------|
| Content | `heading` `text` `image` `quote` `code` `video` `button` `spacer` |
| Components | `card` `gallery` `accordion` `slider` `carousel` `media-object` `testimonial` `tabs` `faq` `table` `cta` `icon-box` `alert` `list` `stats` `pricing` `team` `logo-grid` `timeline` |

Each one has its own page under [`doc/blocks/`](doc/blocks/).

### Reusable fields

`layout`, `cover`, `slider`, `carousel`, `gallery`, `faq`, `video`. Use them
outside the page builder with `extends: fields/<name>`, and render them with the
matching `ui/` snippet. See [`doc/fields/`](doc/fields/).

### Render snippets

`ui/image`, `ui/picture`, `ui/slider`, `ui/carousel`, `ui/gallery`, `ui/faq`,
`ui/video` are public: call them from your own templates.
See [`doc/snippets/`](doc/snippets/).

## Responsive images

Never call `->thumb()` or hand-write an `<img>`. `src/Image.php` generates the
variants (480 to 1600 px for content images, 640 to 1920 px for covers), in the
original format and in WebP, and returns the intrinsic dimensions of the
fallback so every tag carries `width` and `height` against layout shift.

```php
<?php snippet('ui/image', [
  'image' => $page->image(),
  'alt'   => 'Workshop',
  'width' => ['default' => '1/1', 'm' => '1/2'],
]) ?>
```

## Configuration

All options live under the `pixelopen.kirby-uikit-builder` key in
`site/config/config.php`. Each one replaces the matching list of Panel options,
so you can cut the builder down to what a given project actually needs:

```php
return [
    'pixelopen.kirby-uikit-builder' => [
        'layouts'     => ['1/1', '1/2, 1/2', '1/3, 1/3, 1/3'],
        'backgrounds' => [
            ['value' => '',                  'text' => 'None'],
            ['value' => 'uk-section-muted',  'text' => 'Grey'],
        ],
    ],
];
```

Available keys: `layouts`, `backgrounds`, `paddings`, `containers`,
`css-classes`, `fieldsets`, `visibilities`, `scrollspy-animations`.
Full reference in [`doc/configuration.md`](doc/configuration.md).

## Translations

French and English ship with the plugin. Panel strings are never hardcoded: they
all go through `pixelopen.kirby-uikit-builder.*` translation keys, and the smoke
test enforces parity between `translations/fr.php` and `translations/en.php`.

## Adding a block

Drop the YAML in `blueprints/blocks/` and the snippet in `snippets/blocks/`:
`index.php` registers both by `glob()`, there is nothing to wire by hand. Then
add the block to the `fieldsets` list in `blueprints/fields/layout.yml`, or it
will not show up in the Panel picker.

## Development

```bash
composer install
composer test
```

When the plugin is developed inside a host project through a Composer path
repository, any run that reinstalls the package deletes this `vendor/` again:
Kirby's `composer-installer` strips a plugin's bundled vendor directory on
purpose, to avoid a duplicated autoloader in the host. Re-run `composer install`
here before `composer test`.

The smoke test boots Kirby against a temporary fixture and runs 288 checks:
blueprint resolution, blueprint/snippet consistency, unresolved translation
keys, French/English parity, PSR-4 autoloading, Panel icons (existence and
uniqueness), block registration in the layout field, and documentation
coverage. It runs on PHP 8.2 to 8.5 in CI.

## Documentation

- [Overview and quick start](doc/index.md)
- [Configuration](doc/configuration.md)
- [Sections](doc/sections.md)
- [Blocks](doc/blocks/)
- [Reusable fields](doc/fields/)
- [Render snippets](doc/snippets/)

## License

MIT, see [LICENSE](LICENSE).
