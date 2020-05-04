<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Repositories\Contracts\FeedbackContract;
use App\Core\Repositories\FeedbackRepository;
use App\Core\Repositories\Contracts\FeedbackRequestsContract;
use App\Core\Repositories\FeedbackRequestsRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FeedbackContract::class, FeedbackRepository::class);
        $this->app->bind(FeedbackRequestsContract::class, FeedbackRequestsRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
