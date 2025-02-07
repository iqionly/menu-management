<?php

namespace Iqionly\MenuManagement\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Iqionly\MenuManagement\MenuManagement
 */
class MenuManagement extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu-management';
    }
}
