<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{ActivityLogController,
    AdminController,
    AuthController,
    HomeController,
    RoleController,
    SettingController};
use App\Http\Middleware\HttpLoggerMiddleware;
use Illuminate\Support\Facades\Artisan;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tymon\JWTAuth\Facades\JWTAuth;


Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [
            LocaleSessionRedirect::class,
            LaravelLocalizationRedirectFilter::class,
        ]
    ],
    function () {

        Route::get('/test', function () {
            return Route::currentRouteName();
        })->name('test.route');

        Route::get('/', function () {
            return redirect()->route('dashboard.admin.adminHome');
        });

        Route::get('login', function () {
            return redirect()->route('dashboard.admin.login');
        })->name('login');

        Route::group(['prefix'=>'dashboard', 'as'=>'dashboard.'], function () {

            Route::group(['prefix'=>'admin', 'as'=>'admin.'], function () {
                Route::get('login', [AuthController::class, 'index'])->name('login');
                Route::POST('login', [AuthController::class, 'login'])->name('dologin');
                Route::get('change_language/{lang}', [SettingController::class, 'changeLanguage'])->name('change_language');

                Route::group(['middleware' => 'auth:admin'], function () {

                    // dashboard elements
                    Route::get('/', [HomeController::class, 'index'])->name('adminHome');
//                    Route::get('/', function (){ return Route::currentRouteName(); })->name('adminHome');

                    #============================ Admin ====================================
                    Route::resource('/admins', AdminController::class)->except('create','edit','show');
                    Route::get('my_profile', [AdminController::class, 'myProfile'])->name('myProfile');
                    Route::get('my_profile/edit', [AdminController::class, 'editProfile'])->name('myProfile.edit');
                    Route::get('my_profile/edit_profile_image', [AdminController::class, 'editProfileImage'])->name('myProfile.edit.image');
                    Route::post('my_profile/update_profile_image', [AdminController::class, 'updateProfileImage'])->name('myProfile.update.image');
                    Route::post('my_profile/update', [AdminController::class, 'updateProfile'])->name('myProfile.update');
                    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
                    // roles && permissions
                    Route::resource('/roles', RoleController::class)->except('create','edit','show');

                    // advanced routes
//                    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
//                    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
//                    Route::get('secure_settings', [SettingController::class, 'secure_settings'])->name('secure_settings.index');

                    // Setting
                    Route::GET('/settings/{type?}', [SettingController::class,'edit'])->name('settings.edit');
                    Route::PUT('/settings/{id?}/{type?}', [SettingController::class, 'update'])->name('settings.update');

                    Route::post('secure/update', [SettingController::class, 'secure_update'])->name('secure_settings.update');
                    Route::post('secure/disable', [SettingController::class, 'secure_disable'])->name('secure_settings.disable');

                    Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
                    Route::delete('activity_logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity_logs.destroy');
                    Route::resource('http_loggers', \App\Http\Controllers\Admin\HttpLoggerController::class)->withoutMiddleware(HttpLoggerMiddleware::class);

                });
            });

        });

        #=======================================================================
        #============================ ROOT =====================================
        #=======================================================================
        Route::get('/clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('key:generate');
            Artisan::call('storage:link');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Cache Cleared Successfully']);
        });
    }
);




Route::get('/login-by-token', [AuthController::class, 'loginByToken']);
