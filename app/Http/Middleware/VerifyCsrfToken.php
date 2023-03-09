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
        'http://127.0.0.1:8000/v1/register',
        'http://127.0.0.1:8000/v1/admin/register',
        'http://127.0.0.1:8000/v1/admin/add-employee',
        'http://127.0.0.1:8000/v1/admin/update-employee',
        'http://127.0.0.1:8000/v1/admin/delete-employee'
    ];
}
