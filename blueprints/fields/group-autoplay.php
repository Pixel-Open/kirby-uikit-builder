<?php

return [
    'autoplay' => [
        'type'    => 'toggle',
        'label'   => 'pixelopen.kirby-uikit-builder.autoplay.label',
        'default' => false,
    ],
    'autoplay_interval' => [
        'type'    => 'number',
        'label'   => 'pixelopen.kirby-uikit-builder.autoplay_interval.label',
        'default' => 7000,
        'when'    => ['autoplay' => true],
    ],
    'pause_on_hover' => [
        'type'    => 'toggle',
        'label'   => 'pixelopen.kirby-uikit-builder.pause_on_hover.label',
        'default' => true,
        'when'    => ['autoplay' => true],
    ],
];
