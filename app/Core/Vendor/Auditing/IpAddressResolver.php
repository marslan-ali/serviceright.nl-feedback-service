<?php

namespace App\Core\Vendor\Auditing;

use Illuminate\Support\Facades\Request;
use OwenIt\Auditing\Resolvers\IpAddressResolver as BaseResolver;

/**
 * Class IpAddressResolver.
 */
class IpAddressResolver extends BaseResolver
{
    /**
     * {@inheritdoc}
     */
    public static function resolve(): string
    {
        if (is_null(Request::ip())) {
            return 'console';
        }

        return Request::ip();
    }
}
