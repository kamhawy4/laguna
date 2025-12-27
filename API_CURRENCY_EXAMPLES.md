# API Examples - Currency & Area Units

## 📌 Request Headers

### Language Control
```
Accept-Language: en    (English - default)
Accept-Language: ar    (Arabic)
```

### Currency Control
```
X-Currency: AED   (Default - Arab Emirates Dirham)
X-Currency: USD   (United States Dollar)
X-Currency: EUR   (Euro)
X-Currency: GBP   (British Pound)
X-Currency: SAR   (Saudi Riyal)
```

### Area Unit Control
```
X-Area-Unit: sqm    (Square Meters - default)
X-Area-Unit: sqft   (Square Feet)
```

---

## 🔍 Postman Examples

### Example 1: Get Project in USD with sqft

**Request**
```
GET http://localhost:8000/api/projects/1
Headers:
  Accept-Language: en
  X-Currency: USD
  X-Area-Unit: sqft
```

**Response**
```json
{
  "data": {
    "id": 1,
    "name": "Luxury Downtown Development",
    "slug": "luxury-downtown",
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
    "location": "Downtown Dubai",
    "property_type": "residential",
    "status": "published",
    "is_featured": true,
    "created_at": "2025-12-21 10:30:45",
    "updated_at": "2025-12-21 10:30:45"
  }
}
```

---

### Example 2: Get Project in EUR with sqm (default)

**Request**
```
GET http://localhost:8000/api/projects/1
Headers:
  Accept-Language: ar
  X-Currency: EUR
```

**Response**
```json
{
  "data": {
    "id": 1,
    "name": "تطوير فندقي فاخر",
    "pricing": {
      "currency": "EUR",
      "price": 294100,
      "base_price_aed": 1000000
    },
    "area": {
      "value": 100,
      "unit": "sqm",
      "base_value_sqm": 100
    },
    "status": "published"
  }
}
```

---

### Example 3: Get Projects List with Default Currency

**Request**
```
GET http://localhost:8000/api/projects
```

**Response**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Luxury Downtown Development",
      "pricing": {
        "currency": "AED",
        "price": 1000000,
        "base_price_aed": 1000000
      },
      "area": {
        "value": 100,
        "unit": "sqm",
        "base_value_sqm": 100
      }
    },
    {
      "id": 2,
      "name": "Marina Residences",
      "pricing": {
        "currency": "AED",
        "price": 2500000,
        "base_price_aed": 2500000
      },
      "area": {
        "value": 250,
        "unit": "sqm",
        "base_value_sqm": 250
      }
    }
  ],
  "links": {...},
  "meta": {...}
}
```

---

### Example 4: Get Project by Slug with SAR

**Request**
```
GET http://localhost:8000/api/projects/slug/luxury-downtown
Headers:
  X-Currency: SAR
  X-Area-Unit: sqft
```

**Response**
```json
{
  "data": {
    "id": 1,
    "name": "Luxury Downtown Development",
    "slug": "luxury-downtown",
    "pricing": {
      "currency": "SAR",
      "price": 72700,
      "base_price_aed": 1000000
    },
    "area": {
      "value": 1076.40,
      "unit": "sqft",
      "base_value_sqm": 100
    }
  }
}
```

---

### Example 5: Create Project (Admin - Store in Base Currency)

**Request**
```
POST http://localhost:8000/api/projects
Headers:
  Content-Type: application/json
  
Body:
{
  "name": {
    "en": "New Downtown Tower",
    "ar": "برج وسط البلد الجديد"
  },
  "slug": {
    "en": "new-downtown-tower",
    "ar": "برج-وسط-جديد"
  },
  "short_description": {
    "en": "Premium residential tower",
    "ar": "برج سكني فاخر"
  },
  "description": {
    "en": "A stunning new development...",
    "ar": "مشروع جديد مذهل..."
  },
  "price_aed": 1500000,
  "area": 150,
  "location": "Downtown Dubai",
  "property_type": "apartment",
  "status": "published"
}
```

**Response** (Note: Response automatically converts based on headers)
```json
{
  "data": {
    "id": 3,
    "name": "New Downtown Tower",
    "pricing": {
      "currency": "AED",
      "price": 1500000,
      "base_price_aed": 1500000
    },
    "area": {
      "value": 150,
      "unit": "sqm",
      "base_value_sqm": 150
    },
    "status": "published"
  }
}
```

---

## 💱 Conversion Examples

### Price Conversions (from 1,000,000 AED base)

| Currency | Rate | Converted Price |
|----------|------|-----------------|
| AED | 1.0000 | 1,000,000 |
| USD | 0.2723 | 272,300 |
| EUR | 0.2941 | 294,100 |
| GBP | 0.3413 | 341,300 |
| SAR | 0.0727 | 72,700 |

### Area Conversions (from 100 sqm base)

| Unit | Converted Area |
|------|----------------|
| sqm  | 100 |
| sqft | 1,076.40 |

---

## 🧪 Testing Checklist

- [ ] Request with no headers uses defaults (AED, sqm, en)
- [ ] Request with X-Currency: USD returns prices in USD
- [ ] Request with X-Area-Unit: sqft returns area in sqft
- [ ] Request with both headers converts both
- [ ] Request with invalid currency falls back to base (AED)
- [ ] Request with invalid area unit falls back to sqm
- [ ] Language header (Accept-Language) still works with currency headers
- [ ] All three headers work together correctly
- [ ] Response includes base_price_aed and base_value_sqm for reference
- [ ] Prices rounded to 2 decimals correctly
- [ ] Area rounded to 2 decimals correctly

---

## 🔐 Admin Panel

### Manage Currency Rates

**Path**: http://localhost:8000/admin/currency-rates

**Actions**:
1. ✅ View all currencies
2. ✅ Create new currency
3. ✅ Edit exchange rates
4. ✅ Set base currency (only one allowed)
5. ✅ Activate/Deactivate currencies
6. ✅ Delete currency (if not base)

---

## 📊 Conversion Formula Examples

### Price: AED to USD
```
Price in USD = Price in AED × Exchange Rate (USD)
1,000,000 × 0.2723 = 272,300 USD
```

### Price: AED to EUR
```
Price in EUR = Price in AED × Exchange Rate (EUR)
1,000,000 × 0.2941 = 294,100 EUR
```

### Area: sqm to sqft
```
Area in sqft = Area in sqm × 10.764
100 × 10.764 = 1,076.40 sqft
```

---

## 🎯 Key Points

✅ **Base Currency**: All prices stored in AED
✅ **Base Area Unit**: All areas stored in sqm
✅ **Conversions**: Happen in real-time at API level
✅ **Admin Control**: Change rates from Currency Rates admin page
✅ **No Data Loss**: Base values always preserved in response
✅ **Backward Compatible**: Old endpoints still work with defaults
✅ **Easy Frontend**: Send headers, get converted data

---

## ⚠️ Important Notes

1. **Always Store in Base Currency**: When creating projects, price must be in AED
2. **Admin Uses Base Currency**: Filament forms use AED for simplicity
3. **API Converts Automatically**: Frontend sends headers, gets converted response
4. **Static Exchange Rates**: Update from admin panel, no external APIs
5. **Proper Rounding**: All conversions rounded to 2 decimals automatically

---

**Last Updated**: December 26, 2025
**Status**: ✅ Production Ready
