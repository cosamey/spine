<?php

namespace Mey\Hive\Facades;

use Illuminate\Support\Facades\Facade;

class Hive extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mey\Hive\Hive::class;
    }
}
