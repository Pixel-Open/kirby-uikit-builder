# Snippet `ui/faq`

FAQ render helper. Used internally by the `faq` block and the `fields/faq` field. Callable from any template or snippet.

Generates a UIkit accordion with schema.org `FAQPage` microdata for Google rich results.

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `Kirby\Cms\Structure` | `null` | FAQ items: each must have `question` and `answer` fields |
| `title` | `string` | `''` | Optional heading rendered above the accordion |
| `multiple` | `bool` | `false` | Allow multiple items open simultaneously |
| `first_open` | `bool` | `true` | Open the first item by default |
| `icon` | `string\|null` | `null` | UIkit icon name displayed before each question, e.g. `'question'`, `'info'`, `'star'` |

## Examples

### From a page field

```php
snippet('ui/faq', [
    'items' => $page->faq()->toStructure(),
]);
```

### With title and custom options

```php
snippet('ui/faq', [
    'items'      => $page->faq()->toStructure(),
    'title'      => $page->faq_title()->value(),
    'multiple'   => true,
    'first_open' => false,
]);
```

## Notes

- The snippet outputs nothing when `$items` is null or empty, safe to call unconditionally.
- `question` and `answer` are the expected field names in the structure.
- Schema.org markup (`FAQPage` / `Question` / `Answer`) is always included.
