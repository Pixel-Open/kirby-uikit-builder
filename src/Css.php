<?php

namespace PixelOpen\KirbyUikitBuilder;

/**
 * Validates CSS values entered in the Panel before they are injected into a
 * style attribute.
 *
 * Same purpose as Color: htmlspecialchars protects the HTML attribute but lets
 * a semicolon through, so "150px;position:fixed;top:0" would remain a valid
 * run of declarations. The format has to be validated.
 */
class Css
{
    private const UNITS = 'px|rem|em|%|vh|vw|vmin|vmax|pt|pc|cm|mm|in|ch|ex|q';

    /**
     * CSS length usable as is, or null.
     *
     * Accepts a number followed by a known unit, plus bare zero, which CSS
     * allows without a unit.
     */
    public static function length(?string $value): ?string
    {
        $value = trim((string)$value);

        if ($value === '') {
            return null;
        }

        if ($value === '0') {
            return '0';
        }

        return preg_match('/^\d+(\.\d+)?(' . self::UNITS . ')$/i', $value) === 1 ? $value : null;
    }
}
