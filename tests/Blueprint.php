<?php

namespace PixelOpen\KirbyUikitBuilder\Tests;

use Kirby\Data\Data;

/**
 * Reads resolved blueprints, for tests that need to know which fields a block
 * declares and of which type.
 *
 * The plugin's blueprints are registered as closures (see index.php) so that
 * select options get translated; those of Kirby's native blocks, such as
 * "text", are registered as a file path. Both forms are resolved here, so that
 * no caller has to care.
 */
final class Blueprint
{
    /**
     * Field definitions of a block, across all tabs.
     *
     * @return array<string, array>
     */
    public static function blockFields(string $type): array
    {
        return self::flatten(self::read('blocks/' . $type));
    }

    /**
     * Section field definitions, spread across the settings tabs of
     * fields/layout.
     *
     * @return array<string, array>
     */
    public static function layoutFields(): array
    {
        $fields = [];

        foreach (self::read('fields/layout')['settings']['tabs'] ?? [] as $tab) {
            $fields = array_merge($fields, $tab['fields'] ?? []);
        }

        return $fields;
    }

    public static function read(string $name): array
    {
        $extension = Fixture::kirby()->extensions('blueprints')[$name] ?? null;

        if ($extension === null) {
            throw new \InvalidArgumentException("Blueprint introuvable : $name");
        }

        if (is_callable($extension) === true) {
            $extension = $extension();
        }

        if (is_string($extension) === true) {
            $extension = Data::read($extension);
        }

        return is_array($extension) ? $extension : [];
    }

    private static function flatten(array $blueprint): array
    {
        $fields = $blueprint['fields'] ?? [];

        foreach ($blueprint['tabs'] ?? [] as $tab) {
            $fields = array_merge($fields, $tab['fields'] ?? []);
        }

        return $fields;
    }
}
