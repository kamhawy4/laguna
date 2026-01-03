# Currency Rates API - Quick Reference

## API Endpoints Created

### 1. List All Currency Rates
```
GET /api/currency-rates
```
Returns all active currencies (or all currencies if `active_only=false` is passed)

### 2. Get Specific Currency Rate
```
GET /api/currency-rates/{currencyCode}
```
Example: `GET /api/currency-rates/USD`

### 3. Get Base Currency
```
GET /api/currency-rates/base
```
Returns the base currency (AED with exchange_rate = 1.0)

### 4. Get Active Currencies
```
GET /api/currency-rates/active
```
Returns all active currencies (same as endpoint 1 with default parameters)

---

## Response Structure

All endpoints return consistent JSON responses:

```json
{
  "status": "success",
  "message": "Description of action",
  "data": {
    // Currency or array of currencies
  }
}
```

---

## Available Currencies

| Code | Name | Symbol | Exchange Rate (to AED) | Base |
|------|------|--------|------------------------|------|
| AED | Arab Emirates Dirham | د.إ | 1.0 | ✓ |
| USD | United States Dollar | $ | 0.2723 | |
| EUR | Euro | € | 0.2507 | |
| GBP | British Pound | £ | 0.2207 | |
| SAR | Saudi Arabian Riyal | ﷼ | 0.1021 | |

---

## Files Created/Modified

### New Files
- `app/Http/Controllers/Api/CurrencyRateController.php` - API Controller with 4 endpoints
- `app/Http/Resources/CurrencyRateResource.php` - API Resource for response formatting
- `docs/CURRENCY_RATES_API.md` - Full API documentation

### Modified Files
- `routes/api.php` - Added import and route group for currency rates endpoints

---

## Key Features

✅ **Get all currency rates** - List all supported currencies with exchange rates  
✅ **Get specific currency** - Fetch individual currency details by code  
✅ **Get base currency** - Retrieve the base currency (AED)  
✅ **Get active currencies only** - Filter to active currencies  
✅ **Consistent response format** - All endpoints follow same structure  
✅ **Error handling** - Proper error responses for invalid requests  
✅ **Read-only endpoints** - All endpoints are GET (safe to call)  

---

## Usage Examples

### cURL
```bash
# Get all currencies
curl https://your-domain.com/api/currency-rates

# Get specific currency
curl https://your-domain.com/api/currency-rates/USD

# Get base currency
curl https://your-domain.com/api/currency-rates/base

# Get active currencies
curl https://your-domain.com/api/currency-rates/active
```

### JavaScript
```javascript
// Get all currencies
fetch('/api/currency-rates')
  .then(res => res.json())
  .then(data => console.log(data.data));
```

### Postman
Import the collection and navigate to Currency Rates folder, or manually create:
- Method: GET
- URL: `{{base_url}}/api/currency-rates`

---

## Integration with Other Endpoints

The Currency Rates API is automatically integrated with other endpoints like `/api/projects` which use the `X-Currency` header to return prices in different currencies using these exchange rates.

For example:
```bash
# Get projects with prices in USD
curl -H "X-Currency: USD" https://your-domain.com/api/projects
```

---

## Database Schema

The `currency_rates` table structure:

```sql
CREATE TABLE currency_rates (
  id BIGINT PRIMARY KEY
  currency_code VARCHAR(3) UNIQUE
  currency_name VARCHAR(255)
  symbol VARCHAR(10)
  exchange_rate DECIMAL(10, 4)
  is_base_currency BOOLEAN DEFAULT FALSE
  is_active BOOLEAN DEFAULT TRUE
  timestamps
)
```

---

## Validation Rules

All currencies are validated for:
- ✓ 3-letter unique currency codes
- ✓ Positive decimal exchange rates
- ✓ Only one base currency allowed (AED)
- ✓ Active/inactive status management

---

## Next Steps

1. Test all endpoints in Postman or your API client
2. Review full documentation in `docs/CURRENCY_RATES_API.md`
3. Integrate with frontend to fetch and display currency options
4. Update currency rates as needed from admin panel at `/admin/currency-rates`

