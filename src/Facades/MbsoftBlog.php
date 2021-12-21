<?php

namespace Mbsoft31\MbsoftBlog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mbsoft31\MbsoftBlog\MbsoftBlog
 */
class MbsoftBlog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mbsoft-blog';
    }
}
