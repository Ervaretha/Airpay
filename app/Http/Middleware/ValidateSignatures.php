<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateSignatures as Middleware;

class ValidateSignatures extends Middleware
{
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];
}