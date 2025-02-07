<?php

namespace Iqionly\MenuManagement\Interfaces;

/**
 * Driver For TreeView implementation
 */
interface DriverTreeView {
    function breakdown(mixed $collection);
    static function assets();
    static function make($collection);
}