# Configuration

All options are declared under the `pixelopen.kirby-uikit-builder` key in `site/config/config.php`.

```php
return [
    'pixelopen.kirby-uikit-builder' => [
        // options here
    ],
];
```

---

## `layouts`

Replaces the list of available column layouts in the layout field.
**Undefined** → 6 default layouts. **Defined** → full replacement.

```php
'layouts' => ['1/1', '1/2, 1/2', '1/3, 2/3', '2/3, 1/3', '1/3, 1/3, 1/3'],
```

---

## `backgrounds`

Replaces the background color options for sections.
Values are CSS classes applied to the `<section>` element.

```php
'backgrounds' => [
    ['value' => '',                    'text' => 'None'],
    ['value' => 'uk-section-default',  'text' => 'White'],
    ['value' => 'uk-section-muted',    'text' => 'Grey'],
    ['value' => 'uk-section-primary',  'text' => 'Primary'],
    ['value' => 'uk-section-secondary','text' => 'Dark'],
    ['value' => 'bg-brand',            'text' => 'Brand color'],
],
```

---

## `paddings`

Replaces the vertical spacing options for sections.

```php
'paddings' => [
    ['value' => 'uk-section-xsmall', 'text' => 'XS'],
    ['value' => 'uk-section-small',  'text' => 'S'],
    ['value' => '',                  'text' => 'M (default)'],
    ['value' => 'uk-section-large',  'text' => 'L'],
    ['value' => 'uk-section-xlarge', 'text' => 'XL'],
],
```

---

## `containers`

Replaces the content width options for sections.
Values correspond to the suffix of `uk-container-{value}` (empty = plain `uk-container`).

```php
'containers' => [
    ['value' => '',        'text' => 'Normal'],
    ['value' => 'small',   'text' => 'Narrow'],
    ['value' => 'large',   'text' => 'Large'],
    ['value' => 'expand',  'text' => 'Full width'],
],
```

---

## `css-classes`

Appends entries to the predefined CSS class list (section **Advanced** tab).
Native plugin classes are preserved.

```php
'css-classes' => [
    ['value' => 'hero-section',            'text' => 'Hero section'],
    ['value' => 'uk-animation-slide-top',  'text' => 'Slide top animation'],
],
```

---

## `fieldsets`

Extends the block groups available in the layout field.

- **Existing key** (`layout`, `content`, `components`) → blocks are added to the group.
- **New key** → a new group is created.

```php
'fieldsets' => [
    'content'    => ['cta'],
    'components' => ['map'],
    'custom' => [
        'label'     => 'Custom',
        'type'      => 'group',
        'fieldsets' => ['my-block'],
    ],
],
```

Block blueprints and snippets must be defined in the project or another plugin.

---

## `visibilities`

Replaces the responsive visibility options for sections.
Values are UIkit classes applied to the `<section>` element.

```php
'visibilities' => [
    ['value' => '',              'text' => 'All devices'],
    ['value' => 'uk-visible@s',  'text' => 'Tablet and up (≥ 640px)'],
    ['value' => 'uk-visible@m',  'text' => 'Desktop and up (≥ 960px)'],
    ['value' => 'uk-hidden@m',   'text' => 'Mobile and tablet (< 960px)'],
],
```

---

## `scrollspy-animations`

Replaces the entrance animation options for sections.
Values are passed to `uk-scrollspy="cls: ..."`.

```php
'scrollspy-animations' => [
    ['value' => 'uk-animation-fade',               'text' => 'Fade'],
    ['value' => 'uk-animation-scale-up',           'text' => 'Scale up'],
    ['value' => 'uk-animation-slide-top-medium',   'text' => 'Slide from top'],
    ['value' => 'uk-animation-slide-bottom-medium','text' => 'Slide from bottom'],
    ['value' => 'uk-animation-slide-left-medium',  'text' => 'Slide from left'],
    ['value' => 'uk-animation-slide-right-medium', 'text' => 'Slide from right'],
],
```

---

## Full example

```php
return [
    'pixelopen.kirby-uikit-builder' => [
        'layouts' => ['1/1', '1/2, 1/2', '1/3, 2/3', '1/3, 1/3, 1/3'],
        'backgrounds' => [
            ['value' => '',                   'text' => 'None'],
            ['value' => 'uk-section-default', 'text' => 'White'],
            ['value' => 'uk-section-muted',   'text' => 'Grey'],
            ['value' => 'bg-brand',           'text' => 'Brand color'],
        ],
        'css-classes' => [
            ['value' => 'hero-section', 'text' => 'Hero section'],
        ],
        'fieldsets' => [
            'content' => ['cta'],
        ],
    ],
];
```
