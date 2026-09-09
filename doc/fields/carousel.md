# Field `fields/carousel`

Reusable structure field for a multi-column carousel (image + title + text + link per item). Renders via the `ui/carousel` snippet.

## Usage in a blueprint

```yaml
items:
  extends: fields/carousel
```

## Structure fields

| Field | Type | Description |
|-------|------|-------------|
| `item_image` | files | Item image (max 1, page images) |
| `item_title` | text | Item title |
| `item_text` | textarea | Item description |
| `item_link` | url | Optional clickable link |

## PHP template

```php
snippet('ui/carousel', [
    'items' => $page->items()->toStructure(),
]);
```

## Snippet parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `Kirby\Cms\Structure` | `null` | Items from `->toStructure()` |
| `cols_mobile` | `string` | `'1'` | Columns on mobile |
| `cols_tablet` | `string` | `'2'` | Columns on tablet (`@s`) |
| `cols_desktop` | `string` | `'3'` | Columns on desktop (`@m`) |
| `cols_large` | `string\|null` | `null` | Columns on large screen (`@l`) |
| `ratio` | `string\|null` | `null` | Fixed image ratio: `'16:9'` · `'4:3'` · `'3:2'` · `'1:1'` |
| `gap` | `string` | `''` | Grid gap class: `''` · `'uk-grid-small'` · `'uk-grid-medium'` |
| `center` | `bool` | `false` | Center mode: active item centered |
| `arrows` | `bool` | `true` | Show prev/next arrows |
| `arrows_outside` | `bool` | `false` | Arrows outside the slider |
| `arrows_color` | `string` | `'dark'` | Arrow color: `'light'` · `'dark'` |
| `arrows_large` | `bool` | `false` | Large arrows |
| `dotnav` | `bool` | `false` | Show dot navigation |
| `dotnav_color` | `string` | `'dark'` | Dot color: `'light'` · `'dark'` |
| `autoplay` | `bool` | `false` | Auto-advance |
| `interval` | `int` | `5000` | Autoplay interval in ms |
| `pause_hover` | `bool` | `true` | Pause on hover |
| `finite` | `bool` | `false` | No loop |
