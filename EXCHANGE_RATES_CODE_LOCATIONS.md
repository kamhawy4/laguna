# Exchange Rate System - File Structure & Code Locations

## Complete Implementation Map

```
app/
├── Models/
│   └── CurrencyRate.php                          [Stores exchange rate data]
│
├── Services/
│   ├── CurrencyConversionService.php            [Core conversion logic]
│   └── AreaUnitConversionService.php            [Area conversions sqm ↔ sqft]
│
├── Http/
│   ├── Middleware/
│   │   └── SetCurrencyAndAreaUnit.php           [Parse X-Currency header]
│   ├── Kernel.php                               [Register middleware]
│   └── Resources/
│       ├── ProjectResource.php                  [API response - uses conversions]
│       └── ServiceResource.php                  [API response - uses conversions]
│
└── Filament/
    └── Resources/
        └── CurrencyRateResource.php             [Admin dashboard UI]

database/
├── migrations/
│   ├── 2025_12_26_160605_create_currency_rates_table.php
│   ├── 2025_12_26_162537_add_price_aed_to_services_table.php
│   └── 2025_12_26_170718_remove_multi_currency_from_projects_table.php
│
└── seeders/
    └── CurrencyRateSeeder.php                   [Default 5 currencies]

docs/
├── CURRENCY_AREA_SYSTEM.md                     [Full architecture docs]
├── FIXED_EXCHANGE_RATES_IMPLEMENTATION.md      [Complete implementation]
└── QUICK_REFERENCE_EXCHANGE_RATES.md           [Quick start guide]
```

---

## Component 1: CurrencyRate Model

**File:** `app/Models/CurrencyRate.php`

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_code',      // USD, EUR, AED
        'currency_name',      // Full name
        'exchange_rate',      // Numeric value
        'symbol',             // $, €, د.إ
        'is_base_currency',   // Boolean
        'is_active',          // Boolean
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'is_base_currency' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Get base currency
    public static function getBaseCurrency()
    {
        return static::where('is_base_currency', true)->first();
    }

    // Get all active currencies
    public static function getActiveCurrencies()
    {
        return static::where('is_active', true)->get();
    }
}
```

### Key Methods

| Method | Purpose |
|--------|---------|
| `getBaseCurrency()` | Get the base currency (AED) |
| `getActiveCurrencies()` | Get all enabled currencies |

---

## Component 2: CurrencyConversionService

**File:** `app/Services/CurrencyConversionService.php`

### Core Methods

```php
// Convert FROM base (AED) TO other currency
convertFromBase(float $baseAmount, string $targetCurrency): float
// Example: convertFromBase(1000000, 'USD') → 272,300

// Convert FROM other currency TO base (AED)
convertToBase(float $amount, string $sourceCurrency): float
// Example: convertToBase(272300, 'USD') → 1,000,000

// Get exchange rate for specific currency
getExchangeRate(string $currencyCode): ?float
// Example: getExchangeRate('USD') → 0.2723

// Get base currency code
getBaseCurrencyCode(): string
// Example: getBaseCurrencyCode() → 'AED'

// Get all active currencies
getActiveCurrencies(): Collection
// Returns: Collection of CurrencyRate models

// Format price with symbol
formatPrice(float $amount, string $currencyCode): string
// Example: formatPrice(272300, 'USD') → "$ 272,300.00"
```

### Usage Examples

```php
// Inject or instantiate
$service = new CurrencyConversionService();

// Convert 1 million AED to USD
$usd = $service->convertFromBase(1000000, 'USD');
// $usd = 272300

// Convert back to AED
$aed = $service->convertToBase(272300, 'USD');
// $aed = 1000000

// Get all active currencies
$currencies = $service->getActiveCurrencies();
foreach ($currencies as $currency) {
    echo $currency->currency_code; // AED, USD, EUR, etc.
}

// Format for display
$formatted = $service->formatPrice(272300, 'USD');
// "$ 272,300.00"
```

---

## Component 3: SetCurrencyAndAreaUnit Middleware

**File:** `app/Http/Middleware/SetCurrencyAndAreaUnit.php`

### What It Does

1. Extracts `X-Currency` header from request
2. Extracts `X-Area-Unit` header from request
3. Validates against active currencies/units
4. Sets request attributes:
   - `$request->currency`
   - `$request->areaUnit`
5. Defaults to AED and sqm if headers missing

### Code

```php
<?php
namespace App\Http\Middleware;

use App\Services\AreaUnitConversionService;
use App\Services\CurrencyConversionService;
use Closure;
use Illuminate\Http\Request;

class SetCurrencyAndAreaUnit
{
    public function handle(Request $request, Closure $next)
    {
        $currencyService = new CurrencyConversionService();
        $areaService = new AreaUnitConversionService();

        // Parse and validate currency
        $currency = strtoupper($request->header('X-Currency', 'AED'));
        if (!$currencyService->getExchangeRate($currency)) {
            $currency = 'AED';
        }
        $request->currency = $currency;

        // Parse and validate area unit
        $areaUnit = strtolower($request->header('X-Area-Unit', 'sqm'));
        if (!$areaService->isValidUnit($areaUnit)) {
            $areaUnit = 'sqm';
        }
        $request->areaUnit = $areaUnit;

        return $next($request);
    }
}
```

### Registration

**File:** `app/Http/Kernel.php`

```php
protected $middlewareGroups = [
    'api' => [
        // ... other middleware
        \App\Http\Middleware\SetCurrencyAndAreaUnit::class,
    ],
];
```

---

## Component 4: ProjectResource API Response

**File:** `app/Http/Resources/ProjectResource.php`

### Key Section

```php
<?php
namespace App\Http\Resources;

use App\Services\AreaUnitConversionService;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currency = $request->currency ?? 'AED';
        $areaUnit = $request->areaUnit ?? 'sqm';

        $currencyService = new CurrencyConversionService();
        $areaService = new AreaUnitConversionService();

        // Convert price based on header
        $convertedPrice = $currencyService->convertFromBase(
            (float) $this->price_aed, 
            $currency
        );

        // Convert area based on header
        $convertedArea = $areaService->convertFromBase(
            (float) $this->area, 
            $areaUnit
        );

        return [
            'id' => $this->id,
            'name' => $this->name,
            'pricing' => [
                'currency' => $currency,
                'price' => $convertedPrice,
                'base_price_aed' => (float) $this->price_aed,
            ],
            'area' => [
                'value' => $convertedArea,
                'unit' => $areaUnit,
                'base_value_sqm' => (float) $this->area,
            ],
            // ... other fields
        ];
    }
}
```

### How It Works

1. Reads `$request->currency` (set by middleware)
2. Reads `$request->areaUnit` (set by middleware)
3. Uses CurrencyConversionService to convert price
4. Uses AreaUnitConversionService to convert area
5. Returns both converted value AND base value

---

## Component 5: Filament Admin Interface

**File:** `app/Filament/Resources/CurrencyRateResource.php`

### Location in Dashboard

```
Admin Panel
└── Settings
    └── Currency Rates
```

### Features

**Form (Create/Edit)**
```
Field              Validation
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Currency Code      Required, 3 letters, unique
Currency Name      Required, max 255
Symbol             Optional, max 10
Exchange Rate      Required, numeric, positive
Is Base Currency   Only one allowed
Is Active          Toggle switch
```

**Table (List)**
```
Columns: Code, Name, Symbol, Rate, Base, Active
Sorting: By code, rate
Filters: Active, Base Currency
Actions: Edit, Delete (except base)
```

### Key Form Code

```php
public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('currency_code')
            ->label('Currency Code')
            ->required()
            ->length(3)
            ->unique(ignoreRecord: true)
            ->toUpper(),

        Forms\Components\TextInput::make('exchange_rate')
            ->label('Exchange Rate')
            ->numeric()
            ->required()
            ->minValue(0)
            ->step(0.0001)
            ->helperText('Relative to base currency'),

        Forms\Components\Toggle::make('is_base_currency')
            ->label('Base Currency')
            ->helperText('Only one currency can be base'),

        Forms\Components\Toggle::make('is_active')
            ->label('Active')
            ->default(true),
    ]);
}
```

---

## Component 6: Database Schema

**Migration:** `database/migrations/2025_12_26_160605_create_currency_rates_table.php`

### Table Structure

```sql
CREATE TABLE currency_rates (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    currency_code VARCHAR(3) UNIQUE NOT NULL,
    currency_name VARCHAR(255) NULLABLE,
    symbol VARCHAR(10) NULLABLE,
    exchange_rate DECIMAL(10, 4) NOT NULL,
    is_base_currency BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX(currency_code),
    INDEX(is_active),
    INDEX(is_base_currency)
);
```

### Sample Data

```sql
INSERT INTO currency_rates VALUES
(1, 'AED', 'Arab Emirates Dirham', 'د.إ', 1.0000, 1, 1, NOW(), NOW()),
(2, 'USD', 'United States Dollar', '$', 0.2723, 0, 1, NOW(), NOW()),
(3, 'EUR', 'Euro', '€', 0.2941, 0, 1, NOW(), NOW()),
(4, 'GBP', 'British Pound', '£', 0.3413, 0, 1, NOW(), NOW()),
(5, 'SAR', 'Saudi Riyal', NULL, 0.0727, 0, 1, NOW(), NOW());
```

---

## Component 7: Database Seeder

**File:** `database/seeders/CurrencyRateSeeder.php`

```php
<?php
namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;

class CurrencyRateSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'currency_code' => 'AED',
                'currency_name' => 'Arab Emirates Dirham',
                'symbol' => 'د.إ',
                'exchange_rate' => 1.0000,
                'is_base_currency' => true,
                'is_active' => true,
            ],
            [
                'currency_code' => 'USD',
                'currency_name' => 'United States Dollar',
                'symbol' => '$',
                'exchange_rate' => 0.2723,
                'is_base_currency' => false,
                'is_active' => true,
            ],
            // ... more currencies
        ];

        foreach ($currencies as $currency) {
            CurrencyRate::create($currency);
        }
    }
}
```

---

## Component 8: Related Models

### Project Model

**File:** `app/Models/Project.php`

```php
protected $fillable = [
    'price_aed',    // ← Only base currency
    'area',         // ← Only sqm
    // ... other fields
];

protected $casts = [
    'price_aed' => 'decimal:2',
    'area' => 'decimal:2',
    // ... other casts
];
```

### Service Model

**File:** `app/Models/Service.php`

```php
protected $fillable = [
    'price_aed',    // ← Only base currency
    // ... other fields
];

protected $casts = [
    'price_aed' => 'decimal:2',
    // ... other casts
];
```

---

## Data Flow Diagram

```
┌─────────────────────────────────────────┐
│  API Request with Headers               │
│  GET /api/projects/1                    │
│  Headers: X-Currency: USD               │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  SetCurrencyAndAreaUnit Middleware      │
│  - Extract X-Currency header            │
│  - Validate against active currencies   │
│  - Set $request->currency = 'USD'       │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  ProjectResource::toArray()             │
│  - Read $request->currency              │
│  - Instantiate CurrencyConversionService│
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  CurrencyConversionService              │
│  - Query CurrencyRate::where('USD')     │
│  - Get exchange_rate (0.2723)           │
│  - Calculate: 1000000 × 0.2723 = 272300│
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  API Response (JSON)                    │
│  {                                      │
│    "pricing": {                         │
│      "currency": "USD",                 │
│      "price": 272300,                   │
│      "base_price_aed": 1000000          │
│    }                                    │
│  }                                      │
└─────────────────────────────────────────┘
```

---

## How to Modify Exchange Rates Programmatically

### Update via Code

```php
// Update USD rate
\App\Models\CurrencyRate::where('currency_code', 'USD')
    ->update(['exchange_rate' => 0.2750]);

// Add new currency
\App\Models\CurrencyRate::create([
    'currency_code' => 'CNY',
    'currency_name' => 'Chinese Yuan',
    'symbol' => '¥',
    'exchange_rate' => 0.0538,
    'is_base_currency' => false,
    'is_active' => true,
]);

// Deactivate currency
\App\Models\CurrencyRate::where('currency_code', 'SAR')
    ->update(['is_active' => false]);
```

### Update via Dashboard

1. Go to: http://localhost:8000/admin/currency-rates
2. Click Edit on currency
3. Change exchange_rate value
4. Click Save
5. ✅ Changes apply immediately

---

## Testing Points

### Test Conversion Service

```bash
php artisan tinker
>>> $service = new \App\Services\CurrencyConversionService();
>>> $service->convertFromBase(1000000, 'USD');
=> 272300
>>> $service->getActiveCurrencies();
=> Collection with 5 items
```

### Test API Endpoint

```bash
# AED (default)
curl http://localhost:8000/api/projects/1 | jq '.data.pricing'

# USD
curl -H "X-Currency: USD" http://localhost:8000/api/projects/1 | jq '.data.pricing'

# EUR
curl -H "X-Currency: EUR" http://localhost:8000/api/projects/1 | jq '.data.pricing'
```

### Test Admin Interface

1. Go to: http://localhost:8000/admin/currency-rates
2. Edit USD rate to 0.2800
3. Get project via API
4. Verify new conversion is used

---

## Summary

All components are **fully implemented and interconnected**:

✅ Model stores rates
✅ Service provides conversions
✅ Middleware parses headers
✅ Resources use conversions
✅ Dashboard manages rates
✅ Database persists everything

The system is **production-ready and working**!

---

**Last Updated:** December 26, 2025  
**Status:** ✅ Complete & Integrated
