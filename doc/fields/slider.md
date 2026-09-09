# Field `fields/slider`

Reusable structure field for a slideshow (one image + caption + link per slide). Renders via the `ui/slider` snippet.

## Usage in a blueprint

```yaml
slides:
  extends: fields/slider
```

## Structure fields

| Field | Type | Description |
|-------|------|-------------|
| `slide_image` | files | Background image (max 1, page images) |
| `slide_caption` | text | Optional caption displayed bottom-right |
| `slide_link` | url | Optional clickable link on the slide |

## PHP template

```php
snippet('ui/slider', [
    'items' => $page->slides()->toStructure(),
]);
```

## Snippet parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `Kirby\Cms\Structure` | `null` | Slides from `->toStructure()` |
| `ratio` | `string` | `'16:9'` | Aspect ratio: `'16:9'` · `'4:3'` · `'21:9'` · `'1:1'` · `'3:2'` · `'3:1'` |
| `animation` | `string` | `'fade'` | Transition: `'fade'` · `'slide'` · `'scale'` · `'pull'` · `'push'` |
| `autoplay` | `bool` | `false` | Auto-advance slides |
| `interval` | `int` | `7000` | Autoplay interval in ms |
| `pause_hover` | `bool` | `true` | Pause on hover |
| `finite` | `bool` | `false` | No loop |
| `arrows` | `bool` | `true` | Show prev/next arrows |
| `arrows_color` | `string` | `'light'` | Arrow color: `'light'` · `'dark'` |
| `arrows_large` | `bool` | `false` | Large arrows |
| `dotnav` | `bool` | `false` | Show dot navigation |
| `dotnav_color` | `string` | `'light'` | Dot color: `'light'` · `'dark'` |
