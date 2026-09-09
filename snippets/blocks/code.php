<?php
$code        = $block->code()->value() ?? '';
$language    = $block->language()->value() ?: 'text';
$filename    = $block->filename()->value();
$showCopy    = $block->copy()->isTrue();
$lineNumbers = $block->line_numbers()->isTrue();
$uid         = 'code-' . $block->id();

$langLabels = [
    'bash' => 'Bash', 'c' => 'C', 'cpp' => 'C++', 'csharp' => 'C#',
    'css' => 'CSS', 'diff' => 'Diff', 'go' => 'Go', 'graphql' => 'GraphQL',
    'html' => 'HTML', 'java' => 'Java', 'js' => 'JavaScript', 'json' => 'JSON',
    'markdown' => 'Markdown', 'php' => 'PHP', 'python' => 'Python',
    'ruby' => 'Ruby', 'rust' => 'Rust', 'scss' => 'SCSS', 'shell' => 'Shell',
    'sql' => 'SQL', 'swift' => 'Swift', 'text' => 'Text',
    'typescript' => 'TypeScript', 'xml' => 'XML', 'yaml' => 'YAML',
];
$langLabel = $langLabels[$language] ?? strtoupper($language);

$showHeader  = $filename || $language !== 'text';
$copyLabel   = t('pixelopen.kirby-uikit-builder.code.btn.copy', 'Copy');
$copiedLabel = t('pixelopen.kirby-uikit-builder.code.btn.copied', '✓ Copied');
?>
<div class="kb-code-wrapper">

  <?php if ($showHeader): ?>
  <div class="kb-code-header">
    <span class="kb-code-filename"><?= html($filename ?: $langLabel) ?></span>
    <div class="kb-code-header-actions">
      <?php if ($filename && $language !== 'text'): ?>
      <span class="kb-code-lang"><?= html($langLabel) ?></span>
      <?php endif ?>
      <?php if ($showCopy): ?>
      <button type="button" class="kb-code-copy"
        onclick="(function(btn){var el=document.getElementById('<?= $uid ?>');navigator.clipboard.writeText(el.textContent||el.innerText).then(function(){btn.textContent='<?= $copiedLabel ?>';setTimeout(function(){btn.textContent='<?= $copyLabel ?>';},2000);}).catch(function(){});btn.blur();})(this)"><?= $copyLabel ?></button>
      <?php endif ?>
    </div>
  </div>
  <?php elseif ($showCopy): ?>
  <button type="button" class="kb-code-copy kb-code-copy--floating"
    onclick="(function(btn){var el=document.getElementById('<?= $uid ?>');navigator.clipboard.writeText(el.textContent||el.innerText).then(function(){btn.textContent='<?= $copiedLabel ?>';setTimeout(function(){btn.textContent='<?= $copyLabel ?>';},2000);}).catch(function(){});btn.blur();})(this)"><?= $copyLabel ?></button>
  <?php endif ?>

  <div class="kb-code-body">
    <?php if ($lineNumbers):
      $lineCount = max(1, substr_count(rtrim($code), "\n") + 1);
      $gutterContent = implode("\n", range(1, $lineCount));
    ?>
    <div class="kb-code-gutter" aria-hidden="true"><?= $gutterContent ?></div>
    <?php endif ?>
    <pre class="kb-code-pre"><code id="<?= $uid ?>" class="language-<?= html($language) ?>"><?= html($code) ?></code></pre>
  </div>

</div>
