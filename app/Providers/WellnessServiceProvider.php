<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\StatisticsServiceInterface;
use App\Services\StatisticsService;
use Illuminate\Support\ServiceProvider;

class WellnessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StatisticsServiceInterface::class, StatisticsService::class);
    }

    public function boot(): void {}
}
