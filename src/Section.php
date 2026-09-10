<?php

namespace PixelOpen\KirbyUikitBuilder;

use Kirby\Cms\Layout;

class Section
{
    // Widths generated for the srcset of the background image
    public const BG_SRCSET_WIDTHS = [640, 1024, 1280, 1920];

    public static function prepare(Layout $layout): array
    {
        $bg              = $layout->attrs()->background()->value();
        $bgCustomColor   = Color::hex($layout->attrs()->bg_custom_color()->value());
        $bgCustomGradient = ($bg === 'custom') && $layout->attrs()->bg_custom_gradient()->isTrue();
        $bgCustomColor2  = Color::hex($layout->attrs()->bg_custom_color2()->value());
        $bgGradientDir   = Color::gradientDirection($layout->attrs()->bg_gradient_dir()->value(), 'to right');

        $sectionStyle = null;
        if ($bg === 'custom') {
            if ($bgCustomGradient && $bgCustomColor && $bgCustomColor2) {
                $sectionStyle = "background-image: linear-gradient({$bgGradientDir}, {$bgCustomColor}, {$bgCustomColor2})";
            } elseif ($bgCustomColor) {
                $sectionStyle = "background-color: {$bgCustomColor}";
            }
        }
        $padding        = $layout->attrs()->padding()->value();
        $paddingRemove  = $layout->attrs()->padding_remove()->split();
        $bgImage        = $layout->attrs()->bg_image()->toFiles()->first();
        $bgVideoEnabled = $layout->attrs()->bg_video_enable()->isTrue();
        $bgVideoSrc     = $layout->attrs()->bg_video_source()->value() ?: 'file';
        $bgVideoFile    = ($bgVideoEnabled && $bgVideoSrc === 'file')
            ? $layout->attrs()->bg_video_file()->toFiles()->first()
            : null;
        $bgVideoUrl     = ($bgVideoEnabled && $bgVideoSrc === 'url')
            ? $layout->attrs()->bg_video_url()->value()
            : null;
        if ($bgVideoUrl) {
            $bgVideoUrl = str_replace('//www.youtube.com/', '//www.youtube-nocookie.com/', $bgVideoUrl);
        }
        $hasVideo       = (bool)($bgVideoFile || $bgVideoUrl);
        $textColor     = $layout->attrs()->text_color()->value();
        $parallax      = $layout->attrs()->parallax()->isTrue();
        $parallaxSpeed = (int)($layout->attrs()->parallax_speed()->value() ?: -200);
        $eagerImage    = $layout->attrs()->eager_image()->isTrue();
        $container     = $layout->attrs()->container()->value();
        $gridValign      = $layout->attrs()->grid_valign()->value();
        $gridHalign      = $layout->attrs()->grid_halign()->value();
        $gridGap         = $layout->attrs()->grid_gap()->value() ?: 'uk-grid-large';
        $gridDivider     = $layout->attrs()->grid_divider()->isTrue();
        $scrollspy       = $layout->attrs()->scrollspy()->isTrue();
        $scrollspyCls    = $layout->attrs()->scrollspy_cls()->value() ?: 'uk-animation-fade';
        $scrollspyDelay  = (int)$layout->attrs()->scrollspy_delay()->value();
        $scrollspyRepeat = $layout->attrs()->scrollspy_repeat()->isTrue();
        $scrollspyAttr   = $scrollspy
            ? 'uk-scrollspy="cls: ' . htmlspecialchars($scrollspyCls) . '; delay: ' . $scrollspyDelay . ($scrollspyRepeat ? '; repeat: true' : '') . '"'
            : null;

        $shapeDivider       = $layout->attrs()->shape_divider()->isTrue();
        $shapeDividerType   = $layout->attrs()->shape_divider_type()->value() ?: 'curve';
        $shapeDividerPos    = $layout->attrs()->shape_divider_position()->value() ?: 'bottom';
        $shapeDividerColor  = Color::hex($layout->attrs()->shape_divider_color()->value()) ?: '#ffffff';
        $shapeDividerHeight = Css::length($layout->attrs()->shape_divider_height()->value()) ?? '150px';

        $visibility     = $layout->attrs()->visibility()->value();
        $sectionId      = $layout->attrs()->section_id()->value();
        $ariaLabel      = $layout->attrs()->aria_label()->value();
        $overlayHex      = Color::hex($layout->attrs()->overlay_color()->value());
        $overlayOpacity  = (int)($layout->attrs()->overlay_opacity()->value() ?: 40);
        $overlayGradient = $layout->attrs()->overlay_gradient()->isTrue();
        $overlayDir      = Color::gradientDirection($layout->attrs()->overlay_gradient_dir()->value(), 'to bottom');
        $overlayHex2     = Color::hex($layout->attrs()->overlay_color2()->value());

        $overlayStyle = null;
        if ($overlayHex) {
            $rgba1 = Color::toRgba($overlayHex, $overlayOpacity);
            if ($overlayGradient && $overlayHex2) {
                $rgba2 = Color::toRgba($overlayHex2, $overlayOpacity);
                $overlayStyle = "background-image: linear-gradient({$overlayDir}, {$rgba1}, {$rgba2})";
            } else {
                $overlayStyle = "background-color: {$rgba1}";
            }
        }
        $extraClasses  = array_filter([
            ...$layout->attrs()->css_classes()->split(),
            ...(explode(' ', $layout->attrs()->css_classes_custom()->value() ?? '')),
        ]);

        $classes = implode(' ', array_filter([
            'uk-section',
            $bg !== 'custom' ? $bg : null,
            $padding,
            (!$hasVideo && $bgImage) ? 'uk-background-cover' : null,
            $hasVideo ? 'uk-cover-container' : null,
            $textColor,
            ($overlayStyle || $hasVideo || $shapeDivider) ? 'uk-position-relative' : null,
            $visibility,
            ...$paddingRemove,
            ...$extraClasses,
        ]));

        $containerClass = 'uk-container' . ($container ? ' uk-container-' . $container : '');

        // The shape divider is absolutely positioned on top of the section:
        // without reserved room, the content slides under it. The container is
        // therefore offset by its height, on whichever sides it sits.
        $containerStyle = null;
        if ($shapeDivider === true) {
            $containerStyle = ''
                . (in_array($shapeDividerPos, ['top', 'both'], true) ? "padding-top: {$shapeDividerHeight};" : '')
                . (in_array($shapeDividerPos, ['bottom', 'both'], true) ? "padding-bottom: {$shapeDividerHeight};" : '');
        }

        $srcset = $bgImage ? $bgImage->srcset(self::BG_SRCSET_WIDTHS) : null;

        return compact(
            'bg', 'sectionStyle', 'padding', 'bgImage', 'bgVideoFile', 'bgVideoUrl', 'hasVideo', 'textColor',
            'shapeDivider', 'shapeDividerType', 'shapeDividerPos', 'shapeDividerColor', 'shapeDividerHeight',
            'parallax', 'parallaxSpeed', 'eagerImage', 'container',
            'gridValign', 'gridHalign', 'gridGap', 'gridDivider',
            'scrollspy', 'scrollspyAttr',
            'visibility', 'sectionId', 'ariaLabel', 'extraClasses', 'overlayStyle',
            'classes', 'containerClass', 'containerStyle', 'srcset'
        );
    }
}
