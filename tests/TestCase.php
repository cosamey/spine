<?php

namespace Mey\Spine\Tests;

use Mey\Spine\SpineServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SpineServiceProvider::class,
        ];
    }
}
