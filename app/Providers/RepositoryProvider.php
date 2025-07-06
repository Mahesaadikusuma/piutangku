<?php

namespace App\Providers;

use App\Repository\CategoryRepository;
use App\Repository\CustomerRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\Interface\CategoryInterface;
use App\Repository\Interface\CustomerInterface;
use App\Repository\Interface\PermissionInterface;
use App\Repository\Interface\PiutangInterface;
use App\Repository\Interface\ProductInterface;
use App\Repository\Interface\RoleInterface;
use App\Repository\Interface\SettingInterface;
use App\Repository\Interface\TransactionInterface;
use App\Repository\Interface\UserInterface;
use App\Repository\PermissionRepository;
use App\Repository\PiutangRepository;
use App\Repository\ProductRepository;
use App\Repository\RoleRepository;
use App\Repository\SettingRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CategoryInterface::class, CategoryRepository::class);
        $this->app->singleton(CustomerInterface::class, CustomerRepository::class);
        $this->app->singleton(PermissionInterface::class, PermissionRepository::class);
        $this->app->singleton(PiutangInterface::class, PiutangRepository::class);
        $this->app->singleton(ProductInterface::class, ProductRepository::class);
        $this->app->singleton(RoleInterface::class, RoleRepository::class);
        $this->app->singleton(SettingInterface::class, SettingRepository::class);
        $this->app->singleton(TransactionInterface::class, TransactionRepository::class);
        $this->app->singleton(UserInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
