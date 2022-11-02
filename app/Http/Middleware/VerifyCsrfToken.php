<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
         'healthcheck/*',
         'quedrug/api/v2/*',
         'quedrug/api/v2/*',
         'systempersonal/api/v1/*',
         'coreapi/api/v1/*',
         'amp/*',
    ];
}
