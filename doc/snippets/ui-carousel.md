# Snippet `ui/carousel`

Multi-column UIkit carousel (`uk-slider`) for use outside the layout block system. Each item has an image, title, text, and optional link.

For full per-item content blocks, use the `carousel` block inside a layout.

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `Kirby\Cms\Structure` | `null` | Items: each must have `item_image`, `item_title`, `item_text`, `item_link` |
| `cols_mobile` | `string` | `'1'` | Columns on mobile |
| `cols_tablet` | `string` | `'2'` | Columns on tablet (`@s`) |
| `cols_desktop` | `string` | `'3'` | Columns on desktop (`@m`) |
| `cols_large` | `string\|null` | `null` | Columns on large screen (`@l`) |
| `ratio` | `string\|null` | `null` | Fixed image ratio: `'16:9'` · `'4:3'` · `'3:2'` · `'1:1'` |
| `gap` | `string` | `''` | Grid gap class: `''` · `'uk-grid-small'` · `'uk-grid-medium'` · `'uk-grid-large'` |
| `center` | `bool` | `false` | Center mode |
| `arrows` | `bool` | `true` | Show prev/next arrows |
| `arrows_outside` | `bool` | `false` | Arrows outside the slider (`uk-position-center-*-out`) |
| `arrows_color` | `string` | `'dark'` | Arrow color: `'light'` · `'dark'` |
| `arrows_large` | `bool` | `false` | Large arrows |
| `dotnav` | `bool` | `false` | Show dot navigation |
| `dotnav_color` | `string` | `'dark'` | Dot color: `'light'` · `'dark'` |
| `autoplay` | `bool` | `false` | Auto-advance |
| `interval` | `int` | `5000` | Autoplay interval in ms |
| `pause_hover` | `bool` | `true` | Pause on hover |
| `finite` | `bool` | `false` | No loop |

## Example

```php
snippet('ui/carousel', [
    'items'        => $page->items()->toStructure(),
    'cols_tablet'  => '2',
    'cols_desktop' => '3',
    'ratio'        => '4:3',
    'arrows'       => true,
    'dotnav'       => true,
]);
```
