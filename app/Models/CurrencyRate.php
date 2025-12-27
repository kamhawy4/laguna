<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_code',
        'currency_name',
        'exchange_rate',
        'symbol',
        'is_base_currency',
        'is_active',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'is_base_currency' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get base currency
     */
    public static function getBaseCurrency()
    {
        return static::where('is_base_currency', true)->first();
    }

    /**
     * Get active currencies
     */
    public static function getActiveCurrencies()
    {
        return static::where('is_active', true)->get();
    }
}
