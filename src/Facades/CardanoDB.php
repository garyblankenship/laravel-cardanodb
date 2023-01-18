<?php

namespace Vampires\CardanoDB\Facades;

use Illuminate\Support\Facades\Facade;

class CardanoDB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cardanodb';
    }
}
