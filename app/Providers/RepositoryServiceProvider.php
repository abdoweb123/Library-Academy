<?php

namespace App\Providers;

use App\Repository\AdminRepositoryInterface;
use App\Repository\Eloquent\AdminRepository;
use App\Repository\Eloquent\Repository;
use App\Repository\Eloquent\RoleRepository;
use App\Repository\Eloquent\SettingRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\CompanyRepositoryInterface;
use App\Repository\Eloquent\CompanyRepository;
use App\Repository\Eloquent\PersonRepository;
use App\Repository\Eloquent\SectionRepository;
use App\Repository\Eloquent\PublisherRepository;
use App\Repository\Eloquent\BookRepository;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\BorrowingRepository;
use App\Repository\Eloquent\CourseRepository;
use App\Repository\RepositoryInterface;
use App\Repository\RoleRepositoryInterface;
use App\Repository\SettingRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\PersonRepositoryInterface;
use App\Repository\SectionRepositoryInterface;
use App\Repository\PublisherRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use App\Repository\BorrowingRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RepositoryInterface::class, Repository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->singleton(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->singleton(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->singleton(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->singleton(SectionRepositoryInterface::class, SectionRepository::class);
        $this->app->singleton(PublisherRepositoryInterface::class, PublisherRepository::class);
        $this->app->singleton(BookRepositoryInterface::class, BookRepository::class);
        $this->app->singleton(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->singleton(BorrowingRepositoryInterface::class, BorrowingRepository::class);
        $this->app->singleton(CourseRepositoryInterface::class, CourseRepository::class);

    }

    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

} //end class
