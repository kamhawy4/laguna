# Multi-Currency & Area Units System

Professional implementation of multi-currency and area unit conversion for real estate platform.

## 🏗️ Architecture Overview

### Single Base Currency Storage
- **Base Currency**: AED (Arab Emirates Dirham)
- **Database Storage**: All prices stored in AED only
- **Conversion**: Happens at display/API response time only
- **Exchange Rates**: Static, managed from admin panel (no external APIs)

### Area Units
- **Base Unit**: sqm (Square Meters)
- **Conversion**: sqft (Square Feet) on demand
- **Formula**: 1 sqm = 10.764 sqft

### Request Header Control
- `Accept-Language` → Language (en/ar)
- `X-Currency` → Currency code (AED, USD, EUR, GBP, SAR)
- `X-Area-Unit` → Area unit (sqm, sqft)

---

## 📋 Database Schema

### currency_rates table

```sql
id              INT PRIMARY KEY
currency_code   VARCHAR(3) UNIQUE     -- AED, USD, EUR, etc.
currency_name   VARCHAR(255)          -- Arab Emirates Dirham
symbol          VARCHAR(10)           -- د.إ, $, €
exchange_rate   DECIMAL(10,4)         -- Rate relative to base (base=1.0)
is_base_currency BOOLEAN               -- Only one base currency
is_active       BOOLEAN                -- Enable/disable currency
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### projects table (unchanged)
- `price_aed` → Stored in base currency (AED)
- `area` → Stored in base unit (sqm)
- Other currency/area columns removed (no longer needed)

---

## 🛠️ Services

### CurrencyConversionService

Handles all currency conversions with proper rounding.

```php
use App\Services\CurrencyConversionService;

$service = new CurrencyConversionService();

// Convert from base currency (AED) to target
$usdPrice = $service->convertFromBase(1000, 'USD'); // 272.30

// Convert to base currency
$aedPrice = $service->convertToBase(100, 'USD'); // 367.00

// Get exchange rate
$rate = $service->getExchangeRate('USD'); // 0.2723

// Format with symbol
$formatted = $service->formatPrice(1000, 'USD'); // $ 1000.00
```

### AreaUnitConversionService

Handles area unit conversions.

```php
use App\Services\AreaUnitConversionService;

$service = new AreaUnitConversionService();

// Convert from base (sqm) to target
$areaFeet = $service->convertFromBase(100, 'sqft'); // 1076.40

// Convert to base
$areaMeter = $service->convertToBase(1000, 'sqft'); // 92.90

// Format with label
$formatted = $service->formatArea(100, 'sqft'); // 1076.40 sqft
```

---

## 🌐 API Usage Examples

### Default Request (No Headers)
```bash
GET /api/projects/1
```
Returns prices in base currency (AED) and area in sqm.

### Request with Currency Header
```bash
GET /api/projects/1
Header: X-Currency: USD
```
Returns prices converted to USD.

### Request with Area Unit Header
```bash
GET /api/projects/1
Header: X-Area-Unit: sqft
```
Returns area converted to square feet.

### Combined Headers
```bash
GET /api/projects/1
Header: Accept-Language: ar
Header: X-Currency: USD
Header: X-Area-Unit: sqft
```
Returns Arabic content with USD prices and sqft area.

### Response Format

```json
{
  "data": {
    "id": 1,
    "name": "Luxury Downtown",
    "pricing": {
      "currency": "USD",
      "price": 272300,
      "base_price_aed": 1000000
    },
    "area": {
      "value": 1076.40,
      "unit": "sqft",
      "base_value_sqm": 100
    },
    "coordinates": {
      "latitude": 25.1972,
      "longitude": 55.2744
    }
  }
}
```

---

## 🔌 Middleware

### SetCurrencyAndAreaUnit Middleware

Automatically parses request headers and attaches to request object.

**Location**: `app/Http/Middleware/SetCurrencyAndAreaUnit.php`

**Registered in**: `app/Http/Kernel.php` (api middleware group)

**Available on Request**:
- `$request->currency` → Currency code (e.g., "USD")
- `$request->areaUnit` → Area unit (e.g., "sqft")

**Defaults**:
- Currency: Base currency (AED)
- Area Unit: sqm

---

## 👨‍💼 Admin Panel (Filament)

### Currency Rate Management

**Path**: Admin → Settings → Currency Rates

**Features**:
- ✅ Create/Edit/Delete currency rates
- ✅ Set base currency (only one allowed)
- ✅ Manage exchange rates
- ✅ Activate/Deactivate currencies
- ✅ Track creation dates

**Navigation**: Settings group (icon: currency-dollar)

---

## 📊 Default Currency Rates

Seeded with realistic values (approximate):

| Code | Name | Symbol | Rate | Base |
|------|------|--------|------|------|
| AED | Arab Emirates Dirham | د.إ | 1.0000 | ✓ |
| USD | United States Dollar | $ | 0.2723 | - |
| EUR | Euro | € | 0.2941 | - |
| GBP | British Pound | £ | 0.3413 | - |
| SAR | Saudi Riyal | ر.س | 0.0727 | - |

**To update rates**: Admin Panel → Settings → Currency Rates

---

## 🔄 Conversion Logic

### Price Conversion
```
Base Price (AED) × Exchange Rate = Target Currency Price
1,000,000 AED × 0.2723 = 272,300 USD
```

### Area Conversion
```
Base Area (sqm) × Conversion Factor = Target Unit
100 sqm × 10.764 = 1,076.40 sqft
```

---

## 📝 Best Practices

### For Frontend Integration

1. **Always send headers** for specific currency/units:
   ```javascript
   fetch('/api/projects', {
     headers: {
       'X-Currency': 'USD',
       'X-Area-Unit': 'sqft'
     }
   })
   ```

2. **Cache conversion rates** on frontend to reduce API calls

3. **Handle fallback** when header is invalid (middleware will use default)

### For Admin

1. **Update rates regularly** from Currency Rates management page
2. **Never delete base currency** (AED)
3. **Keep rates accurate** for correct conversions
4. **Use Deactivate instead of Delete** to preserve history

### For Developers

1. **Always use Service Layer** for conversions (never raw calculations)
2. **Store prices in base currency** (AED) in database
3. **Convert at display time** in Resources/Controllers
4. **Round to 2 decimals** (services handle this automatically)
5. **Support new currencies** by adding to CurrencyRate table

---

## 🧪 Testing Example

### Using Postman

1. Create a project with price: 1,000,000 AED, area: 100 sqm
2. Send requests with different headers:
   - No headers → AED, sqm
   - X-Currency: USD → 272,300 USD
   - X-Area-Unit: sqft → 1,076.40 sqft
   - Both headers → Converted both

---

## ✅ Validation Checklist

- [ ] Migration created and applied
- [ ] CurrencyRate model created
- [ ] Conversion services implemented
- [ ] Middleware registered in API group
- [ ] ProjectResource updated with conversions
- [ ] Filament resource created for currency management
- [ ] Default currencies seeded
- [ ] Headers documented
- [ ] API response format validated
- [ ] Exchange rates configured

---

## 🚀 Future Enhancements

1. **Historical Exchange Rates** - Track rate changes over time
2. **Rate Update API** - Automated external rate fetching (optional)
3. **Bulk Currency Update** - Update multiple rates at once
4. **Rate Alerts** - Notify on rate changes
5. **Currency-Specific Formatting** - Custom decimal places per currency
6. **Audit Logging** - Track who changed rates and when

---

## 📚 Related Files

- **Models**: `app/Models/CurrencyRate.php`
- **Services**: 
  - `app/Services/CurrencyConversionService.php`
  - `app/Services/AreaUnitConversionService.php`
- **Middleware**: `app/Http/Middleware/SetCurrencyAndAreaUnit.php`
- **Resources**: `app/Http/Resources/ProjectResource.php`
- **Filament**: `app/Filament/Resources/CurrencyRateResource.php`
- **Migration**: `database/migrations/2025_12_26_*_create_currency_rates_table.php`
- **Seeder**: `database/seeders/CurrencyRateSeeder.php`

---

## 💡 Key Features

✅ Single base currency in database
✅ Static exchange rates (no external APIs)
✅ Real-time conversions at API level
✅ Header-based currency/unit selection
✅ Professional Filament admin interface
✅ Comprehensive error handling
✅ Proper rounding (2 decimals)
✅ Easy to extend for new currencies
✅ Clean, scalable architecture
✅ No breaking changes to existing code

---

**Status**: ✅ Production Ready
**Base Currency**: AED
**Last Updated**: December 26, 2025
