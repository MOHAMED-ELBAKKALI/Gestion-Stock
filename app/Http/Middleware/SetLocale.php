<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Log session ID and all session data before trying to get 'locale'
        Log::info('[SetLocale Middleware] Session ID: ' . session()->getId());
        Log::info('[SetLocale Middleware] All Session Data Before Get: ' . json_encode(session()->all()));

        $locale = session('locale');

        // Log the locale retrieved from session at the start of the middleware
        Log::info('[SetLocale Middleware] Retrieved session locale: ' . ($locale ?? 'not set'));

        if ($locale && in_array($locale, ['en', 'es', 'fr', 'ar'])) {
            App::setLocale($locale);
            Log::info('[SetLocale Middleware] App locale set to: ' . App::getLocale());
        } else {
            // If no valid locale in session, use default and log it
            $defaultLocale = config('app.locale');
            App::setLocale($defaultLocale);
            Log::info('[SetLocale Middleware] No valid session locale found or locale invalid. Using default locale: ' . $defaultLocale);
        }

        return $next($request);
    }
}
