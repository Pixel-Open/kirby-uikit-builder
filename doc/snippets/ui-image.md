# Snippet `ui/image`

UIkit image render helper. Used internally by the `image` block, it can also be called directly from any template or snippet.

Handles automatically: WebP via `<picture>`, `uk-img` lazy load, ratio with `uk-cover-container`, shadow, lightbox, clickable link.

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `image` | `Kirby\Cms\File` | `null` | Kirby file object: generates WebP automatically |
| `src` | `string` | `''` | External URL (when no `$image`) |
| `alt` | `string` | `''` | Alt text (falls back to `$image->alt()`) |
| `ratio` | `string\|null` | `null` | Forced ratio: `'16/9'` `'4/3'` `'3/2'` `'1/1'` `'21/9'` `'3/1'` |
| `shadow` | `string` | `''` | Shadow: `'small'` `'medium'` `'large'` `'xlarge'` |
| `lightbox` | `bool` | `false` | Opens image fullscreen on click (`uk-lightbox`) |
| `link` | `string` | `''` | Clickable URL (ignored when `lightbox = true`) |
| `eager` | `bool` | `false` | High priority: disables `uk-img`, adds `loading="eager" fetchpriority="high"` |
| `caption` | `string` | `''` | Caption displayed below the image (HTML accepted), hidden in lightbox mode (becomes `data-caption`) |
| `class` | `string` | `''` | Extra CSS classes on the `uk-cover-container` wrapper |
| `animate` | `string\|null` | `null` | UIkit scrollspy animation class: wraps image in a `uk-scrollspy` div, e.g. `'uk-animation-fade'` |
| `width` | `string\|array\|null` | `null` | Responsive width: string `'1/2'` → `uk-width-1-2` or array `['s'=>'1/2','m'=>'1/3']` → `uk-width-1-2@s uk-width-1-3@m` |
| `sizes` | `string` | `'100vw'` | `sizes` attribute for the responsive srcset, e.g. `'(min-width: 960px) 50vw, 100vw'` |

## Behavior

### Lazy loading
By default (`eager = false`), the snippet uses `uk-img`: the image is only loaded when it enters the viewport. The `src`/`srcset` attributes become `data-src`/`data-srcset`.

For **above-the-fold** images (hero, cover), enable `eager = true` for immediate loading with high priority.

### WebP & responsive srcset
If `$image` is a Kirby file, responsive variants are generated at 480/768/1024/1366/1600px (capped at the file's real width) in both the original format and WebP, served in a `<picture>` with `srcset` + `sizes`. The `src` fallback is the largest generated variant, never the raw upload. Pass `sizes` to match the actual display width (defaults to `100vw`).

SVG (not resizable) and GIF (animation would be lost) are served as-is. Images smaller than 480px get a single WebP conversion without srcset. External images (`src`) do not benefit from any conversion.

The `<img>` tag carries the intrinsic `width`/`height` of the `src` variant so the browser reserves the space before loading (no layout shift). External images (`src`) have unknown dimensions and get no such attributes.

In lightbox mode, the link points to a 1920px variant instead of the original file.

### Ratio
When `ratio` is set, the image is wrapped in a `uk-cover-container` with a computed `padding-top`. The image takes `uk-cover` (`object-fit: cover`). If `shadow` is also set, the shadow applies to the container.

## Examples

### Page cover (above-the-fold)

```php
snippet('ui/image', [
    'image' => $page->cover()->toFile(),
    'ratio' => '16/9',
    'eager' => true,
]);
```

### Clickable image with shadow

```php
snippet('ui/image', [
    'image'  => $page->cover()->toFile(),
    'shadow' => 'medium',
    'link'   => $page->url(),
]);
```

### Lightbox with caption

```php
snippet('ui/image', [
    'image'   => $file,
    'lightbox'=> true,
    'caption' => $file->caption()->value(),
]);
```

### External image with square ratio

```php
snippet('ui/image', [
    'src'   => 'https://example.com/photo.jpg',
    'alt'   => 'Photo',
    'ratio' => '1/1',
]);
```

### Wide hero image

```php
$cover = $page->cover()->toFile();

snippet('ui/image', [
    'image' => $cover,
    'ratio' => '3/1',
    'eager' => true,
    'class' => 'my-hero-image',
]);
```

## Generated HTML

With `image` (Kirby file), `ratio = '16/9'`, `eager = false`:

```html
<div class="uk-cover-container" style="padding-top: 56.25%;">
  <picture>
    <source type="image/webp" data-srcset="image-480x.webp 480w, image-768x.webp 768w, …" data-sizes="100vw">
    <img data-src="image-1600x.jpg" data-srcset="image-480x.jpg 480w, image-768x.jpg 768w, …" data-sizes="100vw" alt="Description" uk-cover uk-img>
  </picture>
</div>
```

With `eager = true`:

```html
<div class="uk-cover-container" style="padding-top: 56.25%;">
  <picture>
    <source type="image/webp" srcset="image-480x.webp 480w, image-768x.webp 768w, …" sizes="100vw">
    <img src="image-1600x.jpg" srcset="image-480x.jpg 480w, image-768x.jpg 768w, …" sizes="100vw" alt="Description" uk-cover loading="eager" fetchpriority="high">
  </picture>
</div>
```
