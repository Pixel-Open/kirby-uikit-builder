# Block: code

Syntax-highlighted code block with optional filename header, copy button, and line numbers. Overrides the native Kirby `code` block.

## Panel fields

### Content tab

| Field | Type | Description |
|-------|------|-------------|
| `code` | textarea (monospace) | The code content |
| `language` | select | Programming language for the `language-*` CSS class |
| `filename` | text | Optional filename shown in the header bar |

### Settings tab

| Field | Default | Description |
|-------|---------|-------------|
| `copy` | `true` | Show a "Copy" button (uses Clipboard API) |
| `line_numbers` | `false` | Show line numbers in a gutter column |

## Rendered HTML

```html
<div class="kb-code-wrapper">
  <!-- Header shown when filename or language != 'text' -->
  <div class="kb-code-header">
    <span class="kb-code-filename">index.php</span>
    <div class="kb-code-header-actions">
      <span class="kb-code-lang">PHP</span>
      <button class="kb-code-copy">Copy</button>
    </div>
  </div>
  <div class="kb-code-body">
    <!-- Gutter shown when line_numbers is true -->
    <div class="kb-code-gutter" aria-hidden="true">1
2
3</div>
    <pre class="kb-code-pre"><code id="code-{id}" class="language-php">...</code></pre>
  </div>
</div>
```

## Notes

- The `language-*` class on `<code>` is compatible with Prism.js and Highlight.js if added later
- The copy button uses `navigator.clipboard.writeText()`, no dependencies
- Line numbers are rendered server-side; CSS `user-select: none` on the gutter prevents them from being copied
- When no header is shown but copy is enabled, the copy button floats over the code block (appears on hover)
