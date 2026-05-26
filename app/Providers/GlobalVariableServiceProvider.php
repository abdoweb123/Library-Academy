<?php

namespace App\Providers;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class GlobalVariableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // 1. Set Carbon locale
        Carbon::setLocale($locale);

        // 2. Set default string length for MySQL compatibility
        Schema::defaultStringLength(191);

        if (!App::runningInConsole() && Schema::hasTable('settings')) {
            // 3. Cache settings more efficiently
            $settings = Setting::select('id','key','value')->get(); // Assuming key-value settings

            // 4. Share settings with all views (if needed)
            View::share('setting', $settings);
        }

        /***  For all views ***/
        if (!App::runningInConsole() && Schema::hasTable('settings')) {

                $settings = Setting::all(); // أو ->get()

            View::share([
                'settings' => $settings,
            ]);
        }



        /*** For specific views ***/
        if (!App::runningInConsole() && Schema::hasTable('admins') && Schema::hasTable('groups')) {

                // Define the global variables
            $adminsCount = DB::table('admins')->count();
            $groupsCount = DB::table('groups')->count();

            // Specify the Blade views you want to share this variable with
            $views = [
                'dashboard.index',
            ];

            // Share the variable with the specified views
            foreach ($views as $view) {
                View::composer($view, function ($view) use ($adminsCount, $groupsCount) {
                    $view->with([
                        'adminsCount'=> $adminsCount,
                        'groupsCount'=> $groupsCount,

                    ]);
                });
            }
        }




    }





} //end of class
