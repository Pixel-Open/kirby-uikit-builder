# Snippet `ui/gallery`

Gallery grid with UIkit lightbox. Used by the `gallery` block and the `fields/gallery` field.

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `files` | `Kirby\Cms\Files` | `null` | Image files collection |
| `cols` | `string` | `'three'` | Columns: `'two'` · `'three'` · `'four'` |
| `masonry` | `bool` | `false` | Natural proportions (`uk-grid="masonry: true"`) instead of uniform crop |
| `hover_style` | `string` | `'dark'` | Hover overlay: `'white'` · `'dark'` · `'primary'` · `'none'` |
| `hover_opacity` | `int` | `70` | Overlay opacity (10–100) |
| `hover_icon` | `string` | `'expand'` | UIkit icon displayed on hover: `'plus'` · `'search'` · `'expand'` · `'eye'` · `''` |
| `hover_caption` | `bool` | `false` | Show alt text on hover |
| `gap` | `string` | `'small'` | Grid gap: `''` · `'small'` · `'medium'` |

## Notes

- Images link to their full-size version with `uk-lightbox`.
- In **cropped** mode (default), images are resized to 900×600 px with `crop: true`.
- In **masonry** mode, images are resized to 900 px wide while preserving their natural ratio.
- WebP thumbnails are generated automatically via `->thumb(['format' => 'webp'])`.
