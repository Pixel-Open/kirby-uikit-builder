# Field `fields/faq`

Reusable structure field for a FAQ (question/answer list). Renders via the `ui/faq` snippet with schema.org `FAQPage` markup.

## Usage in a blueprint

```yaml
# site/blueprints/pages/my-page.yml
fields:
  faq:
    extends: fields/faq
```

## PHP template

```php
snippet('ui/faq', [
    'items' => $page->faq()->toStructure(),
]);
```

## Snippet parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `Kirby\Cms\Structure` | `null` | FAQ items from `->toStructure()` |
| `title` | `string` | `''` | Optional heading above the accordion |
| `multiple` | `bool` | `false` | Allow multiple items open at once |
| `first_open` | `bool` | `true` | Open the first item by default |

## Full example

```php
snippet('ui/faq', [
    'items'      => $page->faq()->toStructure(),
    'title'      => $page->faq_title()->value(),
    'multiple'   => true,
    'first_open' => false,
]);
```

## Notes

- The snippet outputs nothing when `$items` is empty, safe to call unconditionally.
- Schema.org `FAQPage` / `Question` / `Answer` microdata is generated automatically for Google rich results.
- Unlike the `faq` block, the field has no built-in title, multiple, or first_open controls; these are passed at render time.
