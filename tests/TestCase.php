<?php

namespace PixelOpen\KirbyUikitBuilder\Tests;

use Kirby\Cms\App as Kirby;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Base class for the plugin's tests: exposes the Kirby instance booted by the
 * bootstrap, without rebuilding it for every test.
 */
abstract class TestCase extends BaseTestCase
{
    protected Kirby $kirby;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kirby = Fixture::kirby();
    }
}
