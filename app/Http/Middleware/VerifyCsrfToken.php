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
        '/evento-home-totems/*',
        '/firma-check-ins/sign-pad',
        '/admin/reservation-api/*',
        '/admin/call-manager/*',
        '/admin/check-ins/parte-viajero-pdf',
        '/admin/check-ins/pdf-download',
        '/admin/permissions/sync',
        '/alice/*',
        
    ];
}
