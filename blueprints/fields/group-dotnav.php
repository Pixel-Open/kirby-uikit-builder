<?php

return [
    'dotnav_vertical' => [
        'type'    => 'toggle',
        'label'   => 'pixelopen.kirby-uikit-builder.dotnav.vertical.label',
        'default' => false,
    ],
    'dotnav_color' => [
        'type'    => 'select',
        'label'   => 'pixelopen.kirby-uikit-builder.dotnav.color.label',
        'default' => 'light',
        'options' => [
            ['value' => 'light', 'text' => 'pixelopen.kirby-uikit-builder.dotnav.color.light'],
            ['value' => 'dark',  'text' => 'pixelopen.kirby-uikit-builder.dotnav.color.dark'],
        ],
    ],
];
