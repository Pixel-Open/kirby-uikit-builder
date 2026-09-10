<?php

namespace PixelOpen\KirbyUikitBuilder;

/**
 * Validates color values entered in the Panel before they are injected into a
 * style attribute.
 *
 * Escaping is not enough here: htmlspecialchars lets a semicolon through, so
 * "#f00;background-image:url(//elsewhere)" would remain a valid CSS
 * declaration. The format has to be validated, not just escaped.
 */
class Color
{
    /**
     * Hexadecimal color usable as is, or null.
     *
     * Accepts the 3, 4, 6 and 8 digit forms, with or without alpha.
     */
    public static function hex(?string $hex): ?string
    {
        return ($hex && preg_match('/^#[0-9a-f]{3,8}$/i', $hex)) ? $hex : null;
    }

    /**
     * Gradient direction taken from the CSS "to <side> [<side>]" grammar, or
     * the given default.
     */
    public static function gradientDirection(?string $direction, string $default): string
    {
        return ($direction && preg_match('/^to( (top|bottom|left|right)){1,2}$/', $direction))
            ? $direction
            : $default;
    }

    /**
     * Converts to rgba() so an opacity can be applied to a hex color.
     */
    public static function toRgba(string $hex, int $opacity): string
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
}
