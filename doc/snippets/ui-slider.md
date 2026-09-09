# Snippet `ui/slider`

Simplified UIkit slideshow (`uk-slideshow`) for use outside the layout block system. Each slide has an image, an optional caption, and an optional link.

For full per-slide content blocks, use the `slider` block inside a layout.

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `Kirby\Cms\Structure` | `null` | Slides: each must have `slide_image`, `slide_caption`, `slide_link` |
| `ratio` | `string` | `'16:9'` | Aspect ratio: `'16:9'` · `'4:3'` · `'21:9'` · `'1:1'` · `'3:2'` · `'3:1'` |
| `animation` | `string` | `'fade'` | Transition: `'fade'` · `'slide'` · `'scale'` · `'pull'` · `'push'` |
| `autoplay` | `bool` | `false` | Auto-advance slides |
| `interval` | `int` | `7000` | Autoplay interval in ms |
| `pause_hover` | `bool` | `true` | Pause on hover |
| `finite` | `bool` | `false` | No loop |
| `arrows` | `bool` | `true` | Show prev/next arrows |
| `arrows_color` | `string` | `'light'` | Arrow color: `'light'` · `'dark'` |
| `arrows_large` | `bool` | `false` | Large arrows (`uk-slidenav-large`) |
| `dotnav` | `bool` | `false` | Show dot navigation below |
| `dotnav_color` | `string` | `'light'` | Dot color: `'light'` · `'dark'` |

## Example

```php
snippet('ui/slider', [
    'items'     => $page->slides()->toStructure(),
    'ratio'     => '16:9',
    'autoplay'  => true,
    'interval'  => 5000,
    'arrows'    => true,
    'dotnav'    => true,
]);
```
