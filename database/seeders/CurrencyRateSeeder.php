<?php

namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;

class CurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing currency rates
        CurrencyRate::truncate();

        // Define currencies with exchange rates relative to AED (base currency)
        $currencies = [
            [
                'currency_code' => 'AED',
                'currency_name' => 'Arab Emirates Dirham',
                'symbol' => 'د.إ',
                'exchange_rate' => 1.0,
                'is_base_currency' => true,
                'is_active' => true,
            ],
            [
                'currency_code' => 'USD',
                'currency_name' => 'United States Dollar',
                'symbol' => '$',
                'exchange_rate' => 0.2723, // 1 USD = 0.2723 AED (approximately)
                'is_base_currency' => false,
                'is_active' => true,
            ],
            [
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.2941, // 1 EUR = 0.2941 AED (approximately)
                'is_base_currency' => false,
                'is_active' => true,
            ],
            [
                'currency_code' => 'GBP',
                'currency_name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.3413, // 1 GBP = 0.3413 AED (approximately)
                'is_base_currency' => false,
                'is_active' => true,
            ],
            [
                'currency_code' => 'SAR',
                'currency_name' => 'Saudi Riyal',
                'symbol' => 'ر.س',
                'exchange_rate' => 0.0727, // 1 SAR = 0.0727 AED (approximately)
                'is_base_currency' => false,
                'is_active' => true,
            ],
        ];

        foreach ($currencies as $currency) {
            CurrencyRate::create($currency);
        }
    }
}

