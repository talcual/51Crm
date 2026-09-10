<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Apply the locale stored in the session, if any, overriding the app default.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale');

        if ($locale && array_key_exists($locale, config('app.available_locales', []))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
