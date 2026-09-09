<?php
$bp = $layout->attrs()->grid_tablet()->isTrue() ? 's' : 'm';

$defaultWidths = [
    '1/1' => 'uk-width-1-1',
    '1/2' => 'uk-width-1-2@' . $bp,
    '1/3' => 'uk-width-1-3@' . $bp,
    '2/3' => 'uk-width-2-3@' . $bp,
    '1/4' => 'uk-width-1-4@' . $bp,
    '3/4' => 'uk-width-3-4@' . $bp,
    '1/6' => 'uk-width-1-6@' . $bp,
    '5/6' => 'uk-width-5-6@' . $bp,
];

foreach ($layout->columns() as $column):
    $opts         = $column->blocks()->filter('type', 'column-options')->first();
    $defaultWidth = $defaultWidths[(string)$column->width()] ?? 'uk-width-expand';
    $colStyle     = $opts ? $opts->column_style()->value() : '';
    $isCard       = $colStyle === 'card';
    $isTile       = $colStyle === 'tile';

    $classes = implode(' ', array_filter(array_map('trim', [
        $opts ? $opts->mobile_width()->value()  : '',
        $opts ? $opts->tablet_width()->value()  : '',
        $opts ? ($opts->column_width()->value() ?: $defaultWidth) : $defaultWidth,
        $opts ? $opts->column_height()->value() : '',
        $isCard ? 'uk-card uk-card-body'                           : '',
        $isCard ? ($opts->card_color()->value() ?: 'uk-card-default') : '',
        $isCard ? $opts->card_size()->value()                      : '',
        ($isCard && $opts->card_hover()->isTrue()) ? 'uk-card-hover' : '',
        $isTile ? $opts->tile_color()->value()                     : '',
        $opts ? $opts->text_align_mobile()->value()                : '',
        $opts ? $opts->text_align_tablet()->value()                : '',
        $opts ? $opts->text_align()->value()                       : '',
        $opts ? $opts->column_padding()->value()                   : '',
        $opts ? $opts->item_order()->value()                       : '',
        $opts ? $opts->content_valign()->value()                   : '',
        $opts ? $opts->column_class()->value()                     : '',
    ])));

    $inlineStyle = '';
    if ($opts) {
        $min = $opts->column_min_height()->value();
        $max = $opts->column_max_height()->value();
        $inlineStyle = ($min ? 'min-height:' . $min . ';' : '') . ($max ? 'max-height:' . $max . ';' : '');
    }

    $animation = '';
    if ($opts && $opts->animation()->isTrue()) {
        $cls   = $opts->animation_type()->value() ?: 'uk-animation-fade';
        $delay = (int)$opts->animation_delay()->value();
        $animation = 'uk-scrollspy="cls: ' . htmlspecialchars($cls) . '; delay: ' . $delay . '"';
    }

    $colId = $opts ? htmlspecialchars($opts->column_id()->value()) : '';
?>
<div <?= $colId ? 'id="' . $colId . '"' : '' ?> class="<?= htmlspecialchars($classes) ?>"<?= $inlineStyle ? ' style="' . htmlspecialchars($inlineStyle) . '"' : '' ?> <?= $animation ?>>
  <?= $column->blocks() ?>
</div>
<?php endforeach ?>
