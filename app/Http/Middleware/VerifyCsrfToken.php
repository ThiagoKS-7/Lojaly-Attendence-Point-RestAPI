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
        '/v1/register',
        '/v1/login',
        '/v1/logout',
        '/v1/refresh',
        '/v1/admin/register',
        '/v1/admin/add-employee',
        '/v1/admin/update-employee',
        '/v1/admin/delete-employee',
        '/v1/attend'
    ];
}
