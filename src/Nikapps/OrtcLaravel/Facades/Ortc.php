<?php

namespace Nikapps\OrtcLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Ortc extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Ortc';
    }
}
