<?php

namespace Developifynet\Sms4Connect;

use Illuminate\Support\Facades\Facade;

class Sms4Connect extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms4connect';
    }
}