<?php

namespace App\Http\Middleware;

use App\Services\AreaUnitConversionService;
use App\Services\CurrencyConversionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrencyAndAreaUnit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get currency from header, default to base currency
        $currency = $request->header('X-Currency');
        if (!$currency) {
            $currencyService = new CurrencyConversionService();
            $currency = $currencyService->getBaseCurrencyCode();
        }
        $request->currency = strtoupper($currency);

        // Get area unit from header, default to sqm
        $areaUnit = $request->header('X-Area-Unit');
        if (!$areaUnit) {
            $areaUnit = AreaUnitConversionService::UNIT_SQM;
        }
        
        // Validate area unit
        if (!AreaUnitConversionService::isValidUnit($areaUnit)) {
            $areaUnit = AreaUnitConversionService::UNIT_SQM;
        }
        $request->areaUnit = strtolower($areaUnit);

        return $next($request);
    }
}
