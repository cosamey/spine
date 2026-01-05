<?php

namespace Mey\Spine\Facades;

use Illuminate\Support\Facades\Facade;

class Spine extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mey\Spine\Spine::class;
    }
}
