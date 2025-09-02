<?php

namespace App\Providers;

use App\Strategies\Content\AudiobookStrategy;
use App\Strategies\Content\PodcastStrategy;
use App\Services\Channel\ChannelService;
use App\Repositories\Channel\Contents\PodcastRepository;
use App\Repositories\Channel\Contents\AudiobookRepository;
use Illuminate\Support\ServiceProvider;
use Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(PodcastStrategy::class, function () {
        //     return new PodcastStrategy(
        //         app(ChannelService::class), 
        //         app(PodcastRepository::class)
        //     );
        // });

        // $this->app->bind(AudiobookStrategy::class, function () {
        //     return new AudiobookStrategy(
        //         app(ChannelService::class), 
        //         app(AudiobookRepository::class)
        //     );
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::middleware('api')
        ->prefix('api')
        ->group(base_path('routes/api.php'));
    }
}
