<?php
$rows = $block->rows()->toStructure();
if ($rows->isEmpty()) return;

$columns    = $block->columns()->toStructure();
$caption    = $block->caption()->value();
$divider    = $block->divider()->value();
$size       = $block->size()->value();
$hover      = $block->hover()->isTrue() ? ' uk-table-hover' : '';
$justify    = $block->justify()->isTrue() ? ' uk-table-justify' : '';
$responsive = $block->responsive()->value();
$stack      = $responsive === 'stack' ? ' uk-table-responsive' : '';

$tableClass = 'uk-table' . $divider . $size . $hover . $justify . $stack;
$overflow   = $responsive === 'overflow';

$colsArr = [];
foreach ($columns as $col) {
    $colsArr[] = $col;
}
?>
<?php if ($overflow): ?><div class="uk-overflow-auto"><?php endif ?>
<table class="<?= $tableClass ?>">
  <?php if ($caption): ?><caption><?= html($caption) ?></caption><?php endif ?>

  <?php if (!empty($colsArr)): ?>
  <thead>
    <tr>
      <?php foreach ($colsArr as $col):
        $thClass = implode(' ', array_filter([
            $col->align()->value(),
            $col->shrink()->isTrue() ? 'uk-table-shrink' : '',
        ]));
      ?>
      <th<?= $thClass ? ' class="' . $thClass . '"' : '' ?>><?= html($col->label()->value()) ?></th>
      <?php endforeach ?>
    </tr>
  </thead>
  <?php endif ?>

  <tbody>
    <?php foreach ($rows as $row):
      $cells = array_map('trim', explode('|', $row->cells()->value() ?? ''));
    ?>
    <tr>
      <?php foreach ($cells as $i => $cell):
        $col        = $colsArr[$i] ?? null;
        $alignClass = $col ? $col->align()->value() : '';
      ?>
      <td<?= $alignClass ? ' class="' . $alignClass . '"' : '' ?>><?= html($cell) ?></td>
      <?php endforeach ?>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
<?php if ($overflow): ?></div><?php endif ?>
