<?php

namespace PixelOpen\KirbyUikitBuilder\Tests;

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Page;

/**
 * Minimal Kirby tree to test the plugin with no site around it.
 *
 * The plugin is linked into a temporary folder the way it would be in
 * site/plugins/, which faithfully reproduces its real loading: glob() over
 * blueprints and snippets, translations, block methods.
 *
 * The instance is unique for the whole process: Kirby does not like being
 * booted twice, and the tests do not need separate environments.
 */
final class Fixture
{
    private static ?Kirby $kirby = null;
    private static ?string $root = null;

    /**
     * Plugin root (the folder holding index.php).
     */
    public static function plugin(): string
    {
        return dirname(__DIR__);
    }

    /**
     * Root of the temporary tree.
     *
     * Public so that render tests can drop pages and content files in it before
     * reading them through the Kirby API.
     */
    public static function root(): string
    {
        return self::$root ??= sys_get_temp_dir() . '/kirby-uikit-builder-test-' . getmypid();
    }

    /**
     * Booted Kirby instance, with the plugin registered.
     */
    public static function kirby(): Kirby
    {
        return self::$kirby ??= self::boot();
    }

    private static function boot(): Kirby
    {
        $root    = self::root();
        $plugins = $root . '/site/plugins';
        $link    = $plugins . '/kirby-uikit-builder';

        foreach ([$plugins, $root . '/content', $root . '/public'] as $dir) {
            if (is_dir($dir) === false && mkdir($dir, 0777, true) === false) {
                throw new \RuntimeException("Impossible de créer $dir");
            }
        }

        if (is_link($link) === false && symlink(self::plugin(), $link) === false) {
            throw new \RuntimeException("Impossible de lier le plugin dans $link");
        }

        register_shutdown_function(static fn () => self::cleanup());

        return new Kirby([
            'roots' => [
                'index'   => $root . '/public',
                'base'    => $root,
                'site'    => $root . '/site',
                'content' => $root . '/content',
            ]
        ]);
    }

    /**
     * Files of the fixture page, with their real dimensions.
     *
     * The names are those of the demo set versioned in
     * data/storage/content/9_demo/: fixtures therefore point at the same files
     * for the tests and for the demo pages, with no mapping table. Here they
     * are generated gradients, there the real photos; only the dimensions have
     * to match.
     */
    private const JPEG = [
        'slider-1.jpg'    => [2000, 1121],
        'slider-2.jpg'    => [2000, 1332],
        'slider-3.jpg'    => [2000, 1325],
        'small-image.jpg' => [300, 212],
        'team-1.jpg'      => [660, 750],
        'team-2.jpg'      => [660, 750],
        'team-3.jpg'      => [660, 750],
        'vertical-1.jpg'  => [667, 1000],
        'vertical-2.jpg'  => [667, 1000],
        'vertical-3.jpg'  => [667, 1000],
    ];

    private const SVG = [
        'logo-1.svg' => [741, 233],
        'logo-2.svg' => [180, 81],
        'logo-4.svg' => [150, 150],
    ];

    /**
     * Content page carrying generated files, for tests that need real ones:
     * Image::sources() reads actual dimensions and builds variants, which no
     * in-memory object can simulate.
     *
     * Two files have no counterpart in the demo set: anim.gif, to check that a
     * GIF is served as is, and video.mp4, a dummy file that Kirby types from
     * its extension without decoding anything.
     */
    public static function page(): Page
    {
        self::kirby();

        $dir = self::root() . '/content/1_test';

        // test.txt is written last and acts as a marker: a generation
        // interrupted halfway has to be replayed, not taken as complete.
        if (is_file($dir . '/test.txt') === false) {
            if (is_dir($dir) === false) {
                mkdir($dir, 0777, true);
            }

            foreach (self::JPEG as $name => [$width, $height]) {
                self::jpeg($dir . '/' . $name, $width, $height);
            }

            foreach (self::SVG as $name => [$width, $height]) {
                file_put_contents(
                    $dir . '/' . $name,
                    '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width
                    . '" height="' . $height . '"></svg>'
                );
            }

            self::png($dir . '/logo-3.png', 250, 250);
            self::gif($dir . '/anim.gif', 800, 600);
            file_put_contents($dir . '/video.mp4', 'factice');

            file_put_contents($dir . '/test.txt', "Title: Test\n");
        }

        $page = self::kirby()->site()->find('test');

        if ($page === null) {
            throw new \RuntimeException('La page de test n\'a pas été trouvée dans la fixture');
        }

        return $page;
    }

    private static function jpeg(string $path, int $width, int $height): void
    {
        imagejpeg(self::gradient($width, $height), $path, 80);
    }

    private static function png(string $path, int $width, int $height): void
    {
        imagepng(self::gradient($width, $height), $path);
    }

    private static function gif(string $path, int $width, int $height): void
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, 200, 100, 50));
        imagegif($image, $path);
    }

    /**
     * A gradient rather than a flat fill: a uniform image compresses to a file
     * so small that some encoders treat it as a special case.
     */
    private static function gradient(int $width, int $height): \GdImage
    {
        $image = imagecreatetruecolor($width, $height);

        for ($x = 0; $x < $width; $x++) {
            $color = imagecolorallocate($image, (int)(255 * $x / $width), 128, 64);
            imageline($image, $x, 0, $x, $height, $color);
        }

        return $image;
    }

    /**
     * Removes the temporary tree.
     *
     * Safeguard: it only descends under sys_get_temp_dir(), and never follows a
     * symlink. Without that second point, the deletion would walk through
     * site/plugins/kirby-uikit-builder and wipe the plugin sources.
     */
    public static function cleanup(): void
    {
        $root   = self::root();
        $prefix = sys_get_temp_dir() . '/kirby-uikit-builder-test-';

        if (str_starts_with($root, $prefix) === false) {
            return;
        }

        self::remove($root);
        self::$kirby = null;
    }

    private static function remove(string $path): void
    {
        if (is_link($path) === true) {
            @unlink($path);
            return;
        }

        if (is_dir($path) === true) {
            foreach (scandir($path) ?: [] as $entry) {
                if ($entry !== '.' && $entry !== '..') {
                    self::remove($path . '/' . $entry);
                }
            }
            @rmdir($path);
            return;
        }

        if (file_exists($path) === true) {
            @unlink($path);
        }
    }
}
