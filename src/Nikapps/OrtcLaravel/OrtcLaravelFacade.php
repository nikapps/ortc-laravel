<?php
namespace Nikapps\OrtcLaravel;

use Illuminate\Support\Facades\Facade;

class OrtcLaravelFacade extends Facade
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
