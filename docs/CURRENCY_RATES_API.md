# Currency Rates API Documentation

## Overview

The Currency Rates API allows you to retrieve exchange rate information for supported currencies. All endpoints return JSON responses and support the standard Accept-Language header for language-specific content.

## Base URL

```
https://your-domain.com/api/currency-rates
```

## Endpoints

### 1. Get All Currency Rates

**Endpoint:** `GET /api/currency-rates`

**Description:** Retrieve all currency rates (active by default)

**Parameters:**

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `active_only` | boolean | No | `true` | Filter only active currencies |

**Example Request:**

```bash
GET /api/currency-rates
GET /api/currency-rates?active_only=false
```

**Example Response:**

```json
{
  "status": "success",
  "message": "Currency rates retrieved successfully",
  "data": [
    {
      "id": 1,
      "currency_code": "AED",
      "currency_name": "Arab Emirates Dirham",
      "symbol": "د.إ",
      "exchange_rate": 1.0,
      "is_base_currency": true,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    },
    {
      "id": 2,
      "currency_code": "USD",
      "currency_name": "United States Dollar",
      "symbol": "$",
      "exchange_rate": 0.2723,
      "is_base_currency": false,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    },
    {
      "id": 3,
      "currency_code": "EUR",
      "currency_name": "Euro",
      "symbol": "€",
      "exchange_rate": 0.2507,
      "is_base_currency": false,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    }
  ]
}
```

---

### 2. Get Specific Currency Rate

**Endpoint:** `GET /api/currency-rates/{currencyCode}`

**Description:** Retrieve exchange rate for a specific currency

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `currencyCode` | string | Yes | 3-letter currency code (e.g., USD, EUR, AED) |

**Example Request:**

```bash
GET /api/currency-rates/USD
GET /api/currency-rates/EUR
GET /api/currency-rates/AED
```

**Example Response:**

```json
{
  "status": "success",
  "message": "Currency rate retrieved successfully",
  "data": {
    "id": 2,
    "currency_code": "USD",
    "currency_name": "United States Dollar",
    "symbol": "$",
    "exchange_rate": 0.2723,
    "is_base_currency": false,
    "is_active": true,
    "created_at": "2025-12-26T12:00:00Z",
    "updated_at": "2025-12-26T12:00:00Z"
  }
}
```

---

### 3. Get Base Currency

**Endpoint:** `GET /api/currency-rates/base`

**Description:** Retrieve the base currency (typically AED)

**Example Request:**

```bash
GET /api/currency-rates/base
```

**Example Response:**

```json
{
  "status": "success",
  "message": "Base currency retrieved successfully",
  "data": {
    "id": 1,
    "currency_code": "AED",
    "currency_name": "Arab Emirates Dirham",
    "symbol": "د.إ",
    "exchange_rate": 1.0,
    "is_base_currency": true,
    "is_active": true,
    "created_at": "2025-12-26T12:00:00Z",
    "updated_at": "2025-12-26T12:00:00Z"
  }
}
```

---

### 4. Get All Active Currencies

**Endpoint:** `GET /api/currency-rates/active`

**Description:** Retrieve all active currencies (same as GET /api/currency-rates with active_only=true)

**Example Request:**

```bash
GET /api/currency-rates/active
```

**Example Response:**

```json
{
  "status": "success",
  "message": "Active currencies retrieved successfully",
  "data": [
    {
      "id": 1,
      "currency_code": "AED",
      "currency_name": "Arab Emirates Dirham",
      "symbol": "د.إ",
      "exchange_rate": 1.0,
      "is_base_currency": true,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    },
    {
      "id": 2,
      "currency_code": "USD",
      "currency_name": "United States Dollar",
      "symbol": "$",
      "exchange_rate": 0.2723,
      "is_base_currency": false,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    },
    {
      "id": 3,
      "currency_code": "EUR",
      "currency_name": "Euro",
      "symbol": "€",
      "exchange_rate": 0.2507,
      "is_base_currency": false,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    },
    {
      "id": 4,
      "currency_code": "GBP",
      "currency_name": "British Pound",
      "symbol": "£",
      "exchange_rate": 0.2207,
      "is_base_currency": false,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    },
    {
      "id": 5,
      "currency_code": "SAR",
      "currency_name": "Saudi Arabian Riyal",
      "symbol": "﷼",
      "exchange_rate": 0.1021,
      "is_base_currency": false,
      "is_active": true,
      "created_at": "2025-12-26T12:00:00Z",
      "updated_at": "2025-12-26T12:00:00Z"
    }
  ]
}
```

---

## Response Format

All API responses follow a consistent format:

### Success Response

```json
{
  "status": "success",
  "message": "Description of what was retrieved",
  "data": {
    // Response data here
  }
}
```

### Error Response

```json
{
  "status": "error",
  "message": "Error description",
  "data": null
}
```

---

## Supported Currencies

The system currently supports the following currencies:

| Code | Name | Symbol | Base Currency |
|------|------|--------|----------------|
| AED | Arab Emirates Dirham | د.إ | ✓ Yes |
| USD | United States Dollar | $ | No |
| EUR | Euro | € | No |
| GBP | British Pound | £ | No |
| SAR | Saudi Arabian Riyal | ﷼ | No |

---

## Exchange Rates

Exchange rates are stored relative to the base currency (AED). For example:

- **1 AED = 1.0 AED** (base rate)
- **1 USD = 0.2723 AED**
- **1 EUR = 0.2507 AED**
- **1 GBP = 0.2207 AED**
- **1 SAR = 0.1021 AED**

To convert from any currency to another, use the formula:

```
Converted Amount = (Original Amount / Exchange Rate of Source Currency) * Exchange Rate of Target Currency
```

Example: Convert 1000 USD to EUR
```
Converted EUR = (1000 / 0.2723) * 0.2507 = 920.57 EUR
```

---

## Headers

### Supported Headers

- **Accept-Language**: Specify the language for responses (en or ar)
- **X-Currency**: Specify the currency for price conversions in other endpoints (not applicable to currency rates API)

### Example Request with Headers

```bash
curl -X GET "https://your-domain.com/api/currency-rates" \
  -H "Accept-Language: en" \
  -H "Content-Type: application/json"
```

---

## Error Handling

### Common Error Responses

#### 404 - Currency Not Found

```json
{
  "status": "error",
  "message": "Currency rate not found",
  "data": null
}
```

#### 500 - Server Error

```json
{
  "status": "error",
  "message": "Failed to retrieve currency rates: Error details",
  "data": null
}
```

---

## Usage Examples

### JavaScript/Fetch

```javascript
// Get all currency rates
fetch('/api/currency-rates')
  .then(response => response.json())
  .then(data => console.log(data));

// Get specific currency
fetch('/api/currency-rates/USD')
  .then(response => response.json())
  .then(data => console.log(data));

// Get base currency
fetch('/api/currency-rates/base')
  .then(response => response.json())
  .then(data => console.log(data));

// Get active currencies only
fetch('/api/currency-rates/active')
  .then(response => response.json())
  .then(data => console.log(data));
```

### cURL

```bash
# Get all currency rates
curl -X GET "https://your-domain.com/api/currency-rates"

# Get specific currency
curl -X GET "https://your-domain.com/api/currency-rates/USD"

# Get base currency
curl -X GET "https://your-domain.com/api/currency-rates/base"

# Get active currencies
curl -X GET "https://your-domain.com/api/currency-rates/active"
```

### Python

```python
import requests

# Get all currency rates
response = requests.get('https://your-domain.com/api/currency-rates')
data = response.json()
print(data)

# Get specific currency
response = requests.get('https://your-domain.com/api/currency-rates/USD')
data = response.json()
print(data)

# Get base currency
response = requests.get('https://your-domain.com/api/currency-rates/base')
data = response.json()
print(data)

# Get active currencies
response = requests.get('https://your-domain.com/api/currency-rates/active')
data = response.json()
print(data)
```

---

## Rate Limiting

Currently, there are no rate limits on the Currency Rates API. However, rate limiting may be implemented in the future.

---

## Caching

Currency rates are cached for performance. The cache is invalidated whenever a currency rate is updated in the admin panel.

---

## Related Documentation

- [Currency System Overview](./CURRENCY_AREA_SYSTEM.md)
- [Exchange Rates Quick Reference](./QUICK_REFERENCE_EXCHANGE_RATES.md)
- [Projects API](./API_PROJECTS.md) - Uses currency rates for price conversions

---

## Support

For issues or questions about the Currency Rates API, please contact support or create an issue in the project repository.
