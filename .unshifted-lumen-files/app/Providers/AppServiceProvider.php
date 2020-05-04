<?php

namespace App\Providers;

use App\Core\Repositories\Contracts\FeedbackContract;
use App\Core\Repositories\Contracts\FeedbackRequestsContract;
use App\Core\Repositories\FeedbackRepository;
use App\Core\Repositories\FeedbackRequestsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FeedbackRequestsContract::class, FeedbackRequestsRepository::class);
        $this->app->bind(FeedbackContract::class, FeedbackRepository::class);
    }
}
