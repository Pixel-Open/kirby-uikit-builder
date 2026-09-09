# Field `fields/gallery`

Reusable files field for selecting gallery images. Renders via the `ui/gallery` snippet.

## Usage in a blueprint

```yaml
gallery:
  extends: fields/gallery
```

## PHP template

```php
snippet('ui/gallery', [
    'files' => $page->gallery()->toFiles(),
]);
```

## Snippet parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `files` | `Kirby\Cms\Files` | `null` | Image files collection |
| `cols` | `string` | `'three'` | Columns: `'two'` · `'three'` · `'four'` |
| `masonry` | `bool` | `false` | Natural proportions instead of uniform crop |
| `hover_style` | `string` | `'dark'` | Hover overlay: `'white'` · `'dark'` · `'primary'` · `'none'` |
| `hover_opacity` | `int` | `70` | Overlay opacity (10–100) |
| `hover_icon` | `string` | `'expand'` | UIkit icon on hover: `'plus'` · `'search'` · `'expand'` · `'eye'` · `''` |
| `hover_caption` | `bool` | `false` | Show alt text on hover |
| `gap` | `string` | `'small'` | Grid gap: `''` · `'small'` · `'medium'` |

## Full example

```php
snippet('ui/gallery', [
    'files'         => $page->gallery()->toFiles(),
    'cols'          => 'four',
    'masonry'       => true,
    'hover_style'   => 'dark',
    'hover_icon'    => 'expand',
    'hover_caption' => true,
]);
```
