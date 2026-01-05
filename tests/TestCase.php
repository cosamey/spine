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

    protected function getEnvironmentSetUp($app): void
    {
        $migration = include __DIR__.'/../vendor/orchestra/testbench-core/laravel/migrations/0001_01_01_000000_testbench_create_users_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/update_users_table.php.stub';
        $migration->up();
    }
}
