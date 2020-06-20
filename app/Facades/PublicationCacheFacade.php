<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PublicationCacheFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'publication-cache';
    }
}
