<?php

namespace App\Services;

use App\Models\CurrencyRate;

class CurrencyConversionService
{
    /**
     * Convert amount from base currency to target currency
     * 
     * @param float $baseAmount Amount in base currency
     * @param string $targetCurrency Target currency code (e.g., 'USD', 'EUR')
     * @return float
     */
    public function convertFromBase(float $baseAmount, string $targetCurrency): float
    {
        $rate = $this->getExchangeRate($targetCurrency);
        
        if ($rate === null) {
            return $baseAmount; // Return base amount if currency not found
        }

        return round($baseAmount * $rate, 2);
    }

    /**
     * Convert amount from source currency to base currency
     * 
     * @param float $amount Amount in source currency
     * @param string $sourceCurrency Source currency code
     * @return float
     */
    public function convertToBase(float $amount, string $sourceCurrency): float
    {
        $rate = $this->getExchangeRate($sourceCurrency);
        
        if ($rate === null || $rate == 0) {
            return $amount;
        }

        return round($amount / $rate, 2);
    }

    /**
     * Get exchange rate for a currency
     * 
     * @param string $currencyCode
     * @return float|null
     */
    public function getExchangeRate(string $currencyCode): ?float
    {
        $currency = CurrencyRate::where('currency_code', strtoupper($currencyCode))
            ->where('is_active', true)
            ->first();

        return $currency ? (float) $currency->exchange_rate : null;
    }

    /**
     * Get base currency code
     * 
     * @return string
     */
    public function getBaseCurrencyCode(): string
    {
        $base = CurrencyRate::where('is_base_currency', true)->first();
        return $base?->currency_code ?? 'AED';
    }

    /**
     * Get all active currencies
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCurrencies()
    {
        return CurrencyRate::where('is_active', true)->get();
    }

    /**
     * Format price for display with currency symbol
     * 
     * @param float $amount
     * @param string $currencyCode
     * @return string
     */
    public function formatPrice(float $amount, string $currencyCode): string
    {
        $currency = CurrencyRate::where('currency_code', strtoupper($currencyCode))
            ->where('is_active', true)
            ->first();

        if (!$currency) {
            return number_format($amount, 2);
        }

        $formatted = number_format($amount, 2);
        return "{$currency->symbol} {$formatted}";
    }
}
