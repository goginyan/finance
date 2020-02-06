<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Hashids\Hashids;

class HashId extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Hashids::class;
    }
}
