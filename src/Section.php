<?php

namespace PixelOpen\KirbyUikitBuilder;

use Kirby\Cms\Layout;

class Section
{
    // Largeurs générées pour le srcset de l'image de fond
    public const BG_SRCSET_WIDTHS = [640, 1024, 1280, 1920];

    // Les valeurs éditeur injectées dans style="" doivent être validées :
    // une couleur ou une direction inattendue ne doit jamais sortir du format attendu.
    private static function safeHex(?string $hex): ?string
    {
        return ($hex && preg_match('/^#[0-9a-f]{3,8}$/i', $hex)) ? $hex : null;
    }

    private static function safeGradientDir(?string $dir, string $default): string
    {
        return ($dir && preg_match('/^to( (top|bottom|left|right)){1,2}$/', $dir)) ? $dir : $default;
    }

    private static function hexToRgba(string $hex, int $opacity): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return sprintf(
            'rgba(%d, %d, %d, %.2f)',
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
            $opacity / 100
        );
    }

    public static function prepare(Layout $layout): array
    {
        $bg              = $layout->attrs()->background()->value();
        $bgCustomColor   = self::safeHex($layout->attrs()->bg_custom_color()->value());
        $bgCustomGradient = ($bg === 'custom') && $layout->attrs()->bg_custom_gradient()->isTrue();
        $bgCustomColor2  = self::safeHex($layout->attrs()->bg_custom_color2()->value());
        $bgGradientDir   = self::safeGradientDir($layout->attrs()->bg_gradient_dir()->value(), 'to right');

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
        $shapeDividerColor  = self::safeHex($layout->attrs()->shape_divider_color()->value()) ?: '#ffffff';
        $shapeDividerHeight = $layout->attrs()->shape_divider_height()->value() ?: '150px';

        $visibility     = $layout->attrs()->visibility()->value();
        $sectionId      = $layout->attrs()->section_id()->value();
        $ariaLabel      = $layout->attrs()->aria_label()->value();
        $overlayHex      = self::safeHex($layout->attrs()->overlay_color()->value());
        $overlayOpacity  = (int)($layout->attrs()->overlay_opacity()->value() ?: 40);
        $overlayGradient = $layout->attrs()->overlay_gradient()->isTrue();
        $overlayDir      = self::safeGradientDir($layout->attrs()->overlay_gradient_dir()->value(), 'to bottom');
        $overlayHex2     = self::safeHex($layout->attrs()->overlay_color2()->value());

        $overlayStyle = null;
        if ($overlayHex) {
            $rgba1 = self::hexToRgba($overlayHex, $overlayOpacity);
            if ($overlayGradient && $overlayHex2) {
                $rgba2 = self::hexToRgba($overlayHex2, $overlayOpacity);
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

        $srcset = $bgImage ? $bgImage->srcset(self::BG_SRCSET_WIDTHS) : null;

        return compact(
            'bg', 'sectionStyle', 'padding', 'bgImage', 'bgVideoFile', 'bgVideoUrl', 'hasVideo', 'textColor',
            'shapeDivider', 'shapeDividerType', 'shapeDividerPos', 'shapeDividerColor', 'shapeDividerHeight',
            'parallax', 'parallaxSpeed', 'eagerImage', 'container',
            'gridValign', 'gridHalign', 'gridGap', 'gridDivider',
            'scrollspy', 'scrollspyAttr',
            'visibility', 'sectionId', 'ariaLabel', 'extraClasses', 'overlayStyle',
            'classes', 'containerClass', 'srcset'
        );
    }
}
