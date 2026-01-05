<?php

namespace Mey\Hive\Tests;

use Mey\Hive\HiveServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            HiveServiceProvider::class,
        ];
    }
}
