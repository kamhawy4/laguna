<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\AreaGuideController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\SocialMediaLinkController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Projects API Routes
Route::prefix('projects')->name('projects.')->group(function () {
    // Public routes
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/featured', [ProjectController::class, 'getFeatured'])->name('featured');
    Route::get('/slug/{slug}', [ProjectController::class, 'showBySlug'])->name('show-by-slug');
    Route::get('/area-guide/{areaGuideSlug}', [ProjectController::class, 'byAreaGuide'])->name('by-area-guide');
    Route::get('/{id}', [ProjectController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::put('/{id}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/publish', [ProjectController::class, 'publish'])->name('publish');
    Route::post('/{id}/unpublish', [ProjectController::class, 'unpublish'])->name('unpublish');
    Route::post('/{id}/toggle-featured', [ProjectController::class, 'toggleFeatured'])->name('toggle-featured');
});

// Blogs API Routes
Route::prefix('blogs')->name('blogs.')->group(function () {
    // Public routes
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/slug/{slug}', [BlogController::class, 'showBySlug'])->name('show-by-slug');
    Route::get('/{id}', [BlogController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [BlogController::class, 'store'])->name('store');
    Route::put('/{id}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{id}', [BlogController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/publish', [BlogController::class, 'publish'])->name('publish');
    Route::post('/{id}/unpublish', [BlogController::class, 'unpublish'])->name('unpublish');
    Route::post('/{id}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('toggle-featured');
});

// Area Guides API Routes
Route::prefix('area-guides')->name('area-guides.')->group(function () {
    // Public routes
    Route::get('/', [AreaGuideController::class, 'index'])->name('index');
    Route::get('/slug/{slug}', [AreaGuideController::class, 'showBySlug'])->name('show-by-slug');
    Route::get('/{id}', [AreaGuideController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [AreaGuideController::class, 'store'])->name('store');
    Route::put('/{id}', [AreaGuideController::class, 'update'])->name('update');
    Route::delete('/{id}', [AreaGuideController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/publish', [AreaGuideController::class, 'publish'])->name('publish');
    Route::post('/{id}/unpublish', [AreaGuideController::class, 'unpublish'])->name('unpublish');
    Route::post('/{id}/toggle-popular', [AreaGuideController::class, 'togglePopular'])->name('toggle-popular');
    
    // Project sync
    Route::post('/{id}/sync-projects', [AreaGuideController::class, 'syncProjects'])->name('sync-projects');
});

// Team Members API Routes
Route::prefix('team-members')->name('team-members.')->group(function () {
    // Public routes
    Route::get('/', [TeamMemberController::class, 'index'])->name('index');
    Route::get('/{id}', [TeamMemberController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [TeamMemberController::class, 'store'])->name('store');
    Route::put('/{id}', [TeamMemberController::class, 'update'])->name('update');
    Route::delete('/{id}', [TeamMemberController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/publish', [TeamMemberController::class, 'publish'])->name('publish');
    Route::post('/{id}/unpublish', [TeamMemberController::class, 'unpublish'])->name('unpublish');
});

// Testimonials API Routes
Route::prefix('testimonials')->name('testimonials.')->group(function () {
    // Public routes
    Route::get('/', [TestimonialController::class, 'index'])->name('index');
    Route::get('/{id}', [TestimonialController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [TestimonialController::class, 'store'])->name('store');
    Route::put('/{id}', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/{id}', [TestimonialController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/publish', [TestimonialController::class, 'publish'])->name('publish');
    Route::post('/{id}/unpublish', [TestimonialController::class, 'unpublish'])->name('unpublish');
    Route::post('/{id}/toggle-featured', [TestimonialController::class, 'toggleFeatured'])->name('toggle-featured');
});

// Social Media Links API Routes
Route::prefix('social-media-links')->name('social-media-links.')->group(function () {
    // Public routes
    Route::get('/', [SocialMediaLinkController::class, 'index'])->name('index');
    Route::get('/{id}', [SocialMediaLinkController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [SocialMediaLinkController::class, 'store'])->name('store');
    Route::put('/{id}', [SocialMediaLinkController::class, 'update'])->name('update');
    Route::delete('/{id}', [SocialMediaLinkController::class, 'destroy'])->name('destroy');
    
    // Management routes
    Route::post('/{id}/toggle-active', [SocialMediaLinkController::class, 'toggleActive'])->name('toggle-active');
    Route::post('/reorder', [SocialMediaLinkController::class, 'reorder'])->name('reorder');
});

// Services API Routes
Route::prefix('services')->name('services.')->group(function () {
    // Public routes
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/slug/{slug}', [ServiceController::class, 'showBySlug'])->name('show-by-slug');
    Route::get('/{id}', [ServiceController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [ServiceController::class, 'store'])->name('store');
    Route::put('/{id}', [ServiceController::class, 'update'])->name('update');
    Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/publish', [ServiceController::class, 'publish'])->name('publish');
    Route::post('/{id}/unpublish', [ServiceController::class, 'unpublish'])->name('unpublish');
    Route::post('/{id}/toggle-featured', [ServiceController::class, 'toggleFeatured'])->name('toggle-featured');
});

// Settings API Routes
Route::prefix('settings')->name('settings.')->group(function () {
    // Public routes
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::get('/{id}', [SettingController::class, 'show'])->name('show');
    
    // Protected routes (add auth middleware as needed)
    Route::post('/', [SettingController::class, 'store'])->name('store');
    Route::put('/{id}', [SettingController::class, 'update'])->name('update');
    
    // Status management routes
    Route::post('/{id}/toggle-status', [SettingController::class, 'toggleStatus'])->name('toggle-status');
});

// Contact Us API Routes
Route::prefix('contacts')->name('contacts.')->group(function () {
    // Public routes
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::get('/{id}', [ContactController::class, 'show'])->name('show');
    Route::post('/', [ContactController::class, 'store'])->name('store');
    
    // Protected routes (add auth middleware as needed)
    Route::put('/{id}', [ContactController::class, 'update'])->name('update');
    Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
    
    // Status management routes
    Route::post('/{id}/mark-as-read', [ContactController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/{id}/mark-as-closed', [ContactController::class, 'markAsClosed'])->name('mark-as-closed');
});