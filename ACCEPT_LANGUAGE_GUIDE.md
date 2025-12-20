# Accept-Language Header Implementation

## Overview
The API now respects the `Accept-Language` header to return translatable content in the requested language (EN or AR).

## How to Use in Postman

### 1. Get Data in English (Default)
```
GET http://localhost:8000/api/services
Header: Accept-Language: en
```

### 2. Get Data in Arabic
```
GET http://localhost:8000/api/services
Header: Accept-Language: ar
```

### 3. Get Data with Quality Values
```
GET http://localhost:8000/api/services
Header: Accept-Language: ar,en;q=0.9
```
This will try to return Arabic first, then English as fallback.

## Supported Endpoints

All translatable endpoints support Accept-Language header:

- **Projects** - `/api/projects`, `/api/projects/{id}`, `/api/projects/slug/{slug}`
- **Blogs** - `/api/blogs`, `/api/blogs/{id}`, `/api/blogs/slug/{slug}`
- **Services** - `/api/services`, `/api/services/{id}`, `/api/services/slug/{slug}`
- **Testimonials** - `/api/testimonials`, `/api/testimonials/{id}`
- **Settings** - `/api/settings`, `/api/settings/{id}`
- **Area Guides** - `/api/area-guides`, `/api/area-guides/{id}`, `/api/area-guides/slug/{slug}`
- **Team Members** - `/api/team-members`, `/api/team-members/{id}`

## Response Examples

### Without Accept-Language Header (Defaults to English)
```json
{
  "data": {
    "id": 1,
    "title": "Property Management",
    "slug": "property-management",
    "description": "Complete property management solutions...",
    "status": "published"
  }
}
```

### With Accept-Language: ar
```json
{
  "data": {
    "id": 1,
    "title": "إدارة الممتلكات",
    "slug": "ادارة-الممتلكات",
    "description": "حلول إدارة الممتلكات الكاملة...",
    "status": "published"
  }
}
```

## Available Locales

- `en` - English (default)
- `ar` - Arabic

## Postman Setup

### Add Header in Postman:

1. Open your request in Postman
2. Go to the **Headers** tab
3. Add a new header:
   - **Key**: `Accept-Language`
   - **Value**: `en` or `ar`
4. Send the request

### Using Environment Variables:

```json
{
  "key": "Accept-Language",
  "value": "{{language}}",
  "type": "text"
}
```

Then set the `{{language}}` variable to `en` or `ar` in your Postman environment.

## Configuration

If you want to change available locales, edit `config/app.php`:

```php
'available_locales' => ['en', 'ar', 'fr'], // Add more if needed
```

## Implementation Details

- **Middleware**: `App\Http\Middleware\SetLocaleFromAcceptLanguage`
- **Location**: Applied to all API routes
- **Fallback**: If Accept-Language header is not provided or locale is not available, defaults to English (`en`)
- **Parser**: Handles quality factors in Accept-Language header (e.g., `ar,en;q=0.9`)
