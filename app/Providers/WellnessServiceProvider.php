<?php

namespace App\Providers;

use App\Contracts\EntryServiceInterface;
use App\Contracts\MedicationServiceInterface;
use App\Contracts\StatisticsServiceInterface;
use App\Services\EntryService;
use App\Services\MedicationService;
use App\Services\StatisticsService;
use Illuminate\Support\ServiceProvider;

class WellnessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EntryServiceInterface::class, EntryService::class);
        $this->app->bind(MedicationServiceInterface::class, MedicationService::class);
        $this->app->bind(StatisticsServiceInterface::class, StatisticsService::class);
    }

    public function boot(): void
    {
    }
}
