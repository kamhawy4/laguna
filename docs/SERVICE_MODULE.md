# Service Module - Spatie Translatable Implementation

## Overview
The Service module has been refactored to properly use Spatie's Laravel Translatable plugin for multi-language support (EN/AR).

## Key Features

### 1. Translatable Fields
- **title**: Service name (translatable JSON)
- **slug**: URL-friendly identifier (translatable JSON, auto-generated per locale)
- **short_description**: Brief summary (translatable JSON)
- **description**: Full description (translatable JSON, RichEditor)
- **icon**: Font Awesome class or SVG name
- **image**: Optional service image
- **is_featured**: Boolean flag for featured services
- **order**: Display order (for sorting)
- **status**: "draft" or "published"
- **seo_meta**: JSON object with meta_title, meta_description, meta_keywords

### 2. Database Storage
Translatable fields are stored as JSON in the database:
```json
{
  "en": "Service Name",
  "ar": "اسم الخدمة"
}
```

### 3. Model Usage

#### Creating a Service
```php
Service::create([
    'title' => [
        'en' => 'Web Development',
        'ar' => 'تطوير الويب'
    ],
    'slug' => [
        'en' => 'web-development',
        'ar' => 'تطوير-الويب'
    ],
    'short_description' => [
        'en' => 'Professional web development services',
        'ar' => 'خدمات تطوير الويب المحترفة'
    ],
    'description' => [
        'en' => 'Full service web development...',
        'ar' => 'تطوير ويب كامل...'
    ],
    'icon' => 'fas fa-code',
    'status' => 'published',
    'is_featured' => true,
]);
```

#### Retrieving Translations
```php
$service = Service::find(1);

// Get current locale (e.g., 'en')
echo $service->title; // "Web Development"

// Get specific locale
echo $service->getTranslation('title', 'ar'); // "تطوير الويب"

// Check if translation exists
$service->hasTranslation('title', 'en'); // true

// Set translation
$service->setTranslation('title', 'ar', 'تطوير الويب');
$service->save();
```

### 4. API Usage

#### Create Service (POST /api/services)
```json
{
  "title": {
    "en": "Web Development",
    "ar": "تطوير الويب"
  },
  "short_description": {
    "en": "Professional web services",
    "ar": "خدمات ويب محترفة"
  },
  "description": {
    "en": "Comprehensive web development services...",
    "ar": "خدمات تطوير ويب شاملة..."
  },
  "icon": "fas fa-code",
  "status": "published",
  "is_featured": true,
  "order": 1,
  "seo_meta": {
    "meta_title": "Web Development Services",
    "meta_description": "Professional web development",
    "meta_keywords": "web, development, services"
  }
}
```

#### Get Services (GET /api/services)
Query Parameters:
- `status=published` - Filter by status
- `is_featured=1` - Show only featured services
- `search=web` - Search in title/short_description (current locale)
- `per_page=15` - Pagination

#### Get by Slug (GET /api/services/slug/{slug})
Returns service by slug in current locale

#### Update Service (PUT /api/services/{id})
Partial updates supported using `sometimes` validation

#### Status Management
- POST `/api/services/{id}/publish` - Publish service
- POST `/api/services/{id}/unpublish` - Unpublish service
- POST `/api/services/{id}/toggle-featured` - Toggle featured status

### 5. Filament Admin

#### Creating/Editing Services
1. **Service Information Section**
   - Title (stores as JSON for all locales)
   - Short Description (stores as JSON)
   - Full Description (RichEditor, stores as JSON)

2. **Media & Icon Section**
   - Upload service image
   - Set Font Awesome icon class

3. **SEO Metadata Section** (collapsed by default)
   - Meta Title
   - Meta Description
   - Meta Keywords

4. **Status & Settings Section**
   - Status dropdown (Draft/Published)
   - Featured toggle
   - Display order (for drag-to-reorder)

#### Table Features
- Searchable title column (searches current locale)
- Reorderable by dragging
- Filter by status and featured flag
- Show/hide columns

### 6. Service Layer

#### Methods
```php
// Pagination with filters
$service->paginate(
    perPage: 15,
    filters: ['status' => 'published', 'search' => 'web']
);

// Get all (filtered)
$service->all(filters: ['is_featured' => true]);

// Find by ID
$service->find(1);

// CRUD operations
$service->create($data);
$service->update($id, $data);
$service->delete($id);

// Status management
$service->publish($id);
$service->unpublish($id);

// Feature management
$service->toggleFeatured($id);

// Slug generation (per locale)
$service->generateSlug('Web Development'); // Returns ['en' => 'web-development', 'ar' => 'تطوير-الويب']
```

### 7. Configuration

#### Available Locales
Set in `config/app.php`:
```php
'available_locales' => ['en', 'ar'],
```

#### Locale Switching
```php
// Temporarily switch locale
app()->setLocale('ar');
$service = Service::find(1);
echo $service->title; // Arabic title

// Restore locale
app()->setLocale('en');
```

### 8. Scopes

```php
// Get only published services
Service::published()->get();

// Get only featured services
Service::featured()->get();

// Combine scopes
Service::published()->featured()->get();
```

### 9. Slug-Based Lookup

```php
// Find by slug in current locale
$service = Service::findBySlug('web-development');

// Find by slug in specific locale
$service = Service::findBySlug('web-development', 'en');
```

### 10. Data Structure

#### Complete Service Example
```php
[
    'id' => 1,
    'title' => [
        'en' => 'Web Development',
        'ar' => 'تطوير الويب'
    ],
    'slug' => [
        'en' => 'web-development',
        'ar' => 'تطوير-الويب'
    ],
    'short_description' => [
        'en' => 'Professional web development services',
        'ar' => 'خدمات تطوير الويب المحترفة'
    ],
    'description' => [
        'en' => 'Full HTML description with rich text...',
        'ar' => 'وصف HTML كامل مع نص منسق...'
    ],
    'icon' => 'fas fa-code',
    'image' => 'services/web-dev.jpg',
    'is_featured' => true,
    'order' => 1,
    'status' => 'published',
    'seo_meta' => {
        'meta_title' => 'Web Development - Laguna',
        'meta_description' => 'Professional web development services',
        'meta_keywords' => 'web, development, services'
    },
    'created_at' => '2025-12-19T10:30:00Z',
    'updated_at' => '2025-12-19T10:30:00Z'
]
```

## Architecture Pattern

### Clean Architecture Layers
1. **Model** (`App\Models\Service`)
   - Translatable fields definition
   - Query scopes
   - Relationships

2. **Repository** (`App\Repositories\ServiceRepository`)
   - CRUD operations
   - Filtering logic (per locale)
   - Pagination

3. **Service** (`App\Services\ServiceService`)
   - Business logic
   - Slug generation (per locale)
   - Status management
   - Feature toggling

4. **API** (`App\Http\Controllers\Api\ServiceController`)
   - Request handling
   - Resource transformation
   - Error responses

5. **Filament** (`App\Filament\Resources\ServiceResource`)
   - Admin dashboard UI
   - Form validation
   - Table display

## Testing

### Factory Usage
```php
// Create single service
$service = Service::factory()->create();

// Create multiple
$services = Service::factory(5)->create();

// Create with specific data
$service = Service::factory()->create([
    'status' => 'published',
    'is_featured' => true,
]);
```

## Best Practices

1. **Always include both locales** when creating/updating
   ```php
   // Good
   ['title' => ['en' => '...', 'ar' => '...']]
   
   // Avoid - will cause translation issues
   ['title' => ['en' => '...']]
   ```

2. **Use generated slugs** - Let the service generate slugs automatically
   ```php
   // Good - slug auto-generated
   $service->create(['title' => [...], 'description' => [...]])
   
   // If providing slug, ensure both locales
   ['slug' => ['en' => 'web-dev', 'ar' => 'تطوير-ويب']]
   ```

3. **Locale-aware queries** - Repository automatically uses current locale
   ```php
   // Search uses current app()->getLocale()
   $services = repo->filter(['search' => 'web']);
   ```

4. **SEO completeness** - Always fill SEO metadata for better rankings
   ```php
   'seo_meta' => [
       'meta_title' => 'Service Title - 60 chars max',
       'meta_description' => 'Service description - 155 chars max',
       'meta_keywords' => 'keyword1, keyword2, keyword3'
   ]
   ```

## Common Operations

### Get English Services
```php
app()->setLocale('en');
$services = Service::published()->get();
```

### Get Arabic Featured Services
```php
app()->setLocale('ar');
$services = Service::published()->featured()->get();
```

### Search Across Locales (if needed)
```php
// Manual search
Service::where(function ($q) {
    $q->where('title->en', 'like', '%web%')
      ->orWhere('title->ar', 'like', '%ويب%');
})->get();
```

### Update Only One Locale
```php
$service = Service::find(1);
$service->setTranslation('title', 'ar', 'عنوان جديد');
$service->save();
```

## Related Modules
- Projects
- Blog
- Area Guides
- Team Members
- Testimonials
- Social Media Links

All modules follow the same Spatie Translatable pattern for consistency.
