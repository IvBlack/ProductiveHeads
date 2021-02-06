<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BasicResourceCollection extends AnonymousResourceCollection
{
    public static $wrap = 'body';
}
