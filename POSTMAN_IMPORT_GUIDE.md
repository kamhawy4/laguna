# How to Import Postman Collection

## File Location
📁 **File Name:** `Laguna_Life_API_Collection.postman_collection.json`  
📁 **Location:** Root of your project folder

## Import Steps

### Method 1: Import via Postman UI (Easiest)

1. **Open Postman**
2. Click **"Collections"** in the left sidebar
3. Click **"Import"** button (top-left area)
4. Select **"File"** tab
5. Choose **`Laguna_Life_API_Collection.postman_collection.json`** from your project folder
6. Click **"Import"**
7. ✅ Collection is now available in your Collections sidebar!

### Method 2: Drag & Drop

1. **Open Postman**
2. Find the `Laguna_Life_API_Collection.postman_collection.json` file on your computer
3. **Drag and drop** the file into the Postman window
4. Confirm the import
5. ✅ Done!

### Method 3: Copy/Paste File Path

1. **Open Postman**
2. Press `Ctrl+O` (or `Cmd+O` on Mac)
3. Navigate to `Laguna_Life_API_Collection.postman_collection.json`
4. Click **Open**

---

## What's Included in the Collection

### 📊 **Organized into 9 Folders:**

1. **Currency Rates** (4 endpoints)
   - Get All Currency Rates
   - Get Currency by Code
   - Get Base Currency
   - Get Active Currencies

2. **Projects** (5 endpoints)
   - Get All Projects
   - Get Featured Projects
   - Get Projects by Area Guide
   - Get Project by ID
   - Get Project by Slug

3. **Blogs** (3 endpoints)
   - Get All Blogs
   - Get Blog by ID
   - Get Blog by Slug

4. **Area Guides** (3 endpoints)
   - Get All Area Guides
   - Get Area Guide by ID
   - Get Area Guide by Slug

5. **Services** (3 endpoints)
   - Get All Services
   - Get Service by ID
   - Get Service by Slug

6. **Team Members** (2 endpoints)
   - Get All Team Members
   - Get Team Member by ID

7. **Testimonials** (2 endpoints)
   - Get All Testimonials
   - Get Testimonial by ID

8. **Social Media Links** (2 endpoints)
   - Get All Social Media Links
   - Get Social Media Link by ID

9. **Contacts** (3 endpoints)
   - Get All Contacts
   - Get Contact by ID
   - Create Contact

10. **Settings** (2 endpoints)
    - Get All Settings
    - Get Setting by ID

---

## Pre-configured Headers

Each endpoint includes pre-configured headers:

- **Accept-Language**: Language selection (en/ar)
- **X-Currency**: Currency conversion (AED/USD/EUR/GBP/SAR)
- **X-Area-Unit**: Area unit conversion (sqm/sqft)
- **Accept**: application/json

---

## Environment Variables

The collection uses a **base_url** variable:

```
{{base_url}} = http://localhost:8000
```

### To Change Base URL:

1. Click the **eye icon** (top-right) → **Edit Environment Variables**
2. Or in the Collection tab:
   - Click the **Variables** tab
   - Edit **base_url** value
3. Examples:
   - Local: `http://localhost:8000`
   - Development: `https://dev-api.lagunalife.com`
   - Production: `https://api.lagunalife.com`

---

## Quick Start

### 1. Import the Collection
Follow import steps above ⬆️

### 2. Set Your Base URL
Update `{{base_url}}` to your API server URL

### 3. Start Making Requests
- Click any endpoint
- Click **Send**
- See the response

### 4. Modify Request Headers
Before sending, you can modify:
- **Accept-Language**: `en` or `ar`
- **X-Currency**: `AED`, `USD`, `EUR`, `GBP`, or `SAR`
- **X-Area-Unit**: `sqm` or `sqft`

---

## Example API Calls

### Get All Currency Rates
```
GET {{base_url}}/api/currency-rates
```

### Get Projects in USD
```
GET {{base_url}}/api/projects
Headers:
  - X-Currency: USD
  - Accept-Language: en
```

### Get Featured Projects in EUR
```
GET {{base_url}}/api/projects/featured
Headers:
  - X-Currency: EUR
  - Accept-Language: ar
```

### Get Area Guides in English
```
GET {{base_url}}/api/area-guides
Headers:
  - Accept-Language: en
```

---

## Testing Currency Conversions

### Test Different Currencies:

1. **Get projects in AED** (Base currency)
   ```
   Header: X-Currency: AED
   ```

2. **Get projects in USD**
   ```
   Header: X-Currency: USD
   (Prices will be converted using exchange rate: 0.2723)
   ```

3. **Get projects in EUR**
   ```
   Header: X-Currency: EUR
   (Prices will be converted using exchange rate: 0.2507)
   ```

---

## Testing Language Support

### Test Different Languages:

1. **English Response**
   ```
   Header: Accept-Language: en
   ```

2. **Arabic Response**
   ```
   Header: Accept-Language: ar
   ```

---

## Testing Area Units

### Test Different Area Units:

1. **Square Meters (Default)**
   ```
   Header: X-Area-Unit: sqm
   ```

2. **Square Feet**
   ```
   Header: X-Area-Unit: sqft
   (1 sqm = 10.764 sqft)
   ```

---

## Notes

- ✅ All endpoints are **GET** (read-only for public API)
- ✅ No authentication required for public endpoints
- ✅ All responses follow standard JSON format
- ✅ Headers are optional but recommended for features like currency conversion
- ✅ Each request has a description explaining its purpose

---

## Support

If you have issues importing:
1. Make sure the file is not corrupted
2. Try importing via **File > Import > File**
3. Check your Postman version is up-to-date
4. Verify the file path is correct

---

**File Size:** ~35 KB  
**Last Updated:** January 3, 2026  
**Compatible with:** Postman v7.0+  
