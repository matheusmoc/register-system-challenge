<?php

namespace App\Providers;

use App\Contracts\DadosInterface;
use App\Contracts\FilesInterface;
use App\Repositories\DadosRepository;
use App\Services\FileService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DadosInterface::class, DadosRepository::class);
        $this->app->bind(FilesInterface::class, FileService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
