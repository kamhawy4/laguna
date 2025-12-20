<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromAcceptLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get Accept-Language header
        $acceptLanguage = $request->header('Accept-Language');

        // Default to 'en' if not provided
        $locale = 'en';

        if ($acceptLanguage) {
            // Parse Accept-Language header (e.g., "ar,en;q=0.9")
            $locales = explode(',', $acceptLanguage);
            foreach ($locales as $lang) {
                // Remove quality factor (q=0.9) if present
                $lang = trim(explode(';', $lang)[0]);
                
                // Check if this locale is available
                if (in_array($lang, config('app.available_locales', ['en', 'ar']))) {
                    $locale = $lang;
                    break;
                }
            }
        }

        // Set the application locale
        app()->setLocale($locale);

        return $next($request);
    }
}
