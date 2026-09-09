# Field `fields/cover`

Ready-to-use file field for selecting a page's cover image.

## Usage in a blueprint

```yaml
# site/blueprints/pages/default.yml
fields:
  cover:
    extends: fields/cover
```

The field automatically filters page files to show only images (`page.files.filterBy("type", "image")`), with a 16:9 preview on a dark background.

## PHP template

```php
$cover = $page->cover()->toFile();

if ($cover) {
    snippet('ui/image', [
        'image' => $cover,
        'ratio' => '16/9',
        'eager' => true,
    ]);
}
```

See [ui/image](../snippets/ui-image.md) for all rendering parameters.
