<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyRateResource;
use App\Models\CurrencyRate;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of all currency rates.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = CurrencyRate::query();

            // Filter by active status if requested
            if ($request->boolean('active_only', true)) {
                $query->where('is_active', true);
            }

            // Sort by is_base_currency first, then by currency_code
            $rates = $query->orderByDesc('is_base_currency')
                ->orderBy('currency_code')
                ->get();

            return $this->successResponse(
                CurrencyRateResource::collection($rates),
                'Currency rates retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve currency rates: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display a specific currency rate by currency code.
     */
    public function show(string $currencyCode): JsonResponse
    {
        try {
            $rate = CurrencyRate::where('currency_code', strtoupper($currencyCode))->firstOrFail();

            return $this->successResponse(
                new CurrencyRateResource($rate),
                'Currency rate retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Currency rate not found',
                404
            );
        }
    }

    /**
     * Get the base currency.
     */
    public function getBaseCurrency(): JsonResponse
    {
        try {
            $baseCurrency = CurrencyRate::where('is_base_currency', true)->firstOrFail();

            return $this->successResponse(
                new CurrencyRateResource($baseCurrency),
                'Base currency retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Base currency not found',
                404
            );
        }
    }

    /**
     * Get all active currencies.
     */
    public function getActiveCurrencies(Request $request): JsonResponse
    {
        try {
            $currencies = CurrencyRate::where('is_active', true)
                ->orderByDesc('is_base_currency')
                ->orderBy('currency_code')
                ->get();

            return $this->successResponse(
                CurrencyRateResource::collection($currencies),
                'Active currencies retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve active currencies: ' . $e->getMessage(),
                500
            );
        }
    }
}
