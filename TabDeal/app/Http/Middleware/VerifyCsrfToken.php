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
        'login','noti_seen','msg_seen','update_trade','delete_data','change_status','update_profle','update_current_balance','update_profle_business'
        //
    ];
}
