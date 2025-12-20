<?php

namespace App\Providers;

use App\Contracts\Repositories\BaseRepositoryInterface;
use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Contracts\Repositories\BlogRepositoryInterface;
use App\Contracts\Repositories\AreaGuideRepositoryInterface;
use App\Contracts\Repositories\TeamMemberRepositoryInterface;
use App\Contracts\Repositories\TestimonialRepositoryInterface;
use App\Contracts\Repositories\SocialMediaLinkRepositoryInterface;
use App\Contracts\Repositories\ServiceRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Contracts\Services\BaseServiceInterface;
use App\Contracts\Services\ProjectServiceInterface;
use App\Contracts\Services\BlogServiceInterface;
use App\Contracts\Services\AreaGuideServiceInterface;
use App\Contracts\Services\TeamMemberServiceInterface;
use App\Contracts\Services\TestimonialServiceInterface;
use App\Contracts\Services\SocialMediaLinkServiceInterface;
use App\Contracts\Services\ServiceServiceInterface;
use App\Contracts\Services\SettingServiceInterface;
use App\Contracts\Services\ContactServiceInterface;
use App\Repositories\ProjectRepository;
use App\Repositories\BlogRepository;
use App\Repositories\AreaGuideRepository;
use App\Repositories\TeamMemberRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\SocialMediaLinkRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SettingRepository;
use App\Repositories\ContactRepository;
use App\Services\ProjectService;
use App\Services\BlogService;
use App\Services\AreaGuideService;
use App\Services\TeamMemberService;
use App\Services\TestimonialService;
use App\Services\SocialMediaLinkService;
use App\Services\ServiceService;
use App\Services\SettingService;
use App\Services\ContactService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind base interfaces here
        // Example: $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        // Example: $this->app->bind(BaseServiceInterface::class, BaseService::class);
        
        // Bind Project repository
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        
        // Bind Project service
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
        
        // Bind Blog repository
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        
        // Bind Blog service
        $this->app->bind(BlogServiceInterface::class, BlogService::class);
        
        // Bind AreaGuide repository
        $this->app->bind(AreaGuideRepositoryInterface::class, AreaGuideRepository::class);
        
        // Bind AreaGuide service
        $this->app->bind(AreaGuideServiceInterface::class, AreaGuideService::class);
        
        // Bind TeamMember repository
        $this->app->bind(TeamMemberRepositoryInterface::class, TeamMemberRepository::class);
        
        // Bind TeamMember service
        $this->app->bind(TeamMemberServiceInterface::class, TeamMemberService::class);
        
        // Bind Testimonial repository
        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);
        
        // Bind Testimonial service
        $this->app->bind(TestimonialServiceInterface::class, TestimonialService::class);
        
        // Bind SocialMediaLink repository
        $this->app->bind(SocialMediaLinkRepositoryInterface::class, SocialMediaLinkRepository::class);
        
        // Bind SocialMediaLink service
        $this->app->bind(SocialMediaLinkServiceInterface::class, SocialMediaLinkService::class);
        
        // Bind Service repository
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        
        // Bind Service service
        $this->app->bind(ServiceServiceInterface::class, ServiceService::class);
        
        // Bind Setting repository
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        
        // Bind Setting service
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        
        // Bind Contact repository
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        
        // Bind Contact service
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
