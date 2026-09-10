<?php

namespace PixelOpen\KirbyUikitBuilder;

class Url
{
    /**
     * Schemes allowed in an href built from an editor field.
     *
     * An allow list rather than a deny list: an unknown scheme is rejected,
     * which covers "vbscript:", "data:text/html" and whatever browsers add
     * next.
     */
    public const SAFE_SCHEMES = ['http', 'https', 'mailto', 'tel', 'ftp', 'ftps', 'sms'];

    /**
     * URL usable as is in an href, or null.
     *
     * Escaping is not enough: htmlspecialchars('javascript:alert(1)') leaves
     * the string untouched, and the link still runs on click. The scheme has
     * to be validated separately from escaping.
     *
     * Accepted: absolute URLs using the schemes above, relative paths,
     * anchors, protocol-relative URLs ("//host/x"). Anything rejected becomes
     * null, and the calling snippet then emits no link at all rather than an
     * empty one.
     */
    public static function safe(?string $url): ?string
    {
        $url = trim((string)$url);

        if ($url === '') {
            return null;
        }

        // Browsers ignore control characters and whitespace before the colon:
        // "java\tscript:x" runs. Scheme detection therefore works on a copy
        // stripped of those characters.
        $probe = strtolower(preg_replace('/[\x00-\x20]+/', '', $url) ?? '');

        if (preg_match('#^([a-z][a-z0-9+.-]*):#', $probe, $matches) === 1) {
            return in_array($matches[1], self::SAFE_SCHEMES, true) ? $url : null;
        }

        return $url;
    }
}
