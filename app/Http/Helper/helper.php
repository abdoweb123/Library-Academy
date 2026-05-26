<?php

use App\Models\Company;
use App\Models\Country;
use App\Models\RefType;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

include 'times.php';

if (!function_exists('getFile')) {
    function getFile($image)
    {
        if (str_starts_with($image,'https://')){
            return $image;
        }elseif ($image != null) {
            // Remove the 'storage/' prefix from the path
            $relativePath = str_replace('storage/', '', $image);
            // Check if the file exists in the 'public' disk
            if (Storage::disk('public')->exists($relativePath)) {
                // Generate the correct URL for the file
                return asset('storage/' . $relativePath);
            } else {
                // Return the default image if the file does not exist
                return asset('assets/uploads/empty.png');
            }
        } else {
            // Return the default image if no image is provided
            return asset('assets/uploads/empty.png');
        }
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return auth()->guard('admin');
    }
}
if (!function_exists('setting')) {
    function setting()
    {
        return \App\Models\Setting::first();
    }
}



if (!function_exists('loggedAdmin')) {
    function loggedAdmin($field = null)
    {
        return auth()->guard('admin')->user()->$field;
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->guard('user');
    }
}

if (!function_exists('lang')) {

    function lang()
    {

        return Config::get('app.locale');
    }
}


if (!function_exists('flang')) {

    function flang($en, $ar)
    {
        if (lang() == 'ar')
            return $ar;
        else
            return $en;
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        if ($number === '' || $number === null || $number == 0) return '';
        if (abs((float)$number) < 0.0001) return '';
        $decimals = country()->decimals ?? 3;
        return number_format((float)$number, $decimals);
    }
}




if (!function_exists('formatIfPositive')) {
    function formatIfPositive($value)
    {
        return ($value > 0) ? formatNumber($value) : '';
    }
}


if (!function_exists('formatBalanceWithType')) {
    function formatBalanceWithType($value, $type)
    {
        return ($value > 0) ? formatNumber($value) . ' ' . $type : '';
    }
}





if (!function_exists('trans_model')) {

    function trans_model($model, $word)
    {

        return $model->{$word . '_' . app()->getlocale()};
    }
}

if (!function_exists('trns')) {
    function trns($key)
    {
        $locale = app()->getLocale();
        $langPath = resource_path("lang/{$locale}");
        $filePath = $langPath . '/file.php';

        // البحث عن الترجمة
        foreach (File::allFiles($langPath) as $file) {
            $filename = $file->getFilenameWithoutExtension();
            $translations = include $file->getRealPath();

            if (is_array($translations) && array_key_exists($key, $translations)) {
                return $translations[$key];
            }
        }

        try {
            $readableKey = preg_replace('/(?<!^)[A-Z]/', ' $0', str_replace('_', ' ', $key));

            if ($locale !== 'en') {

                $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl={$locale}&dt=t&q=" . urlencode($readableKey);
            
                $response = @file_get_contents($url);
            
                if ($response) {
                    $result = json_decode($response, true);
                    $defaultValue = $result[0][0][0] ?? $readableKey;
                } else {
                    $defaultValue = ucwords($readableKey);
                }
            
            } else {
                $defaultValue = ucwords($readableKey);
            }
        } catch (\Exception $e) {
            $readableKey = preg_replace('/(?<!^)[A-Z]/', ' $0', str_replace('_', ' ', $key));
            $defaultValue = ucwords($readableKey);
            \Log::warning("❌ فشل الترجمة: {$e->getMessage()}");
        }

        
        // كتابة المفتاح للملف
        $translations = file_exists($filePath) ? include $filePath : [];
        if (!is_array($translations)) {
            $translations = [];
        }

        $translations[$key] = $defaultValue;

        try {
            $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
            $content = str_replace('\\\\', '\\', $content); // إصلاح الـ escape الزائد
            File::put($filePath, $content);
            \Log::info("✅ تم حفظ المفتاح '{$key}' في {$filePath}");
        } catch (\Exception $e) {
            \Log::error("❌ خطأ في حفظ الملف: {$e->getMessage()}");
        }

        return $defaultValue;
    }
}





if (!function_exists('routeActive')) {
    function routeActive($routeName, $class = 'active')
    {
        if (Route::currentRouteName() == $routeName) {
            return $class;
        }
    }
}

if (!function_exists('arrRouteActive')) {
    function arrRouteActive($routesName, $class = 'open')
    {
        $routes = is_array($routesName) ? $routesName : [$routesName];
        return in_array(Route::currentRouteName(), $routes) ? $class : '';
    }
}



if (!function_exists('getTranslationsFromData')) {

    function getTranslationsFromData(array $data, string $fieldPrefix = 'name_', array $existingTranslations = []): array
    {
        $supportedLangs = config('app.locales');
        $translations = [];

        foreach ($supportedLangs as $langCode => $languageName) {
            if (isset($data[$fieldPrefix . $langCode])) {
                $translations[$langCode] = $data[$fieldPrefix . $langCode];
            } else {
                $translations[$langCode] = $existingTranslations[$langCode] ?? '';
            }
        }

        return $translations;
    }
}
if (!function_exists('checkVariable')) {

    function checkVariable($var, $do, $default = null)
    {
        if (!is_null($var)) {
            return $do;
        } else {
            return $default;
        }
    }
}

if (!function_exists('noImage')) {
    function noImage(): string
    {
        return asset('assets/uploads/empty.png');
    }
}


if (!function_exists('get_user_file')) {
    function get_user_file($image)
    {
        if ($image != null) {
            if (!file_exists($image)) {
                return noImage();
            } else {
                return asset($image);
            }
        } else {
            return  noImage();
        }
    }
}




/**
Get the image URL for an entity or return a default image if not available.
 **/
if (!function_exists('getModelImage')) {
    function getModelImage(?string $imagePath, string $defaultImage = 'img/empty_image.png'): string
    {
        if ($imagePath) {
            $path = public_path($imagePath);
            if (file_exists($path)) {
                return asset($imagePath);
            }
        }
        return asset($defaultImage);
    }
}



function format_number($number)
{
    return number_format($number, country()->decimals, '.', '');
}


function lang($lang = null)
{
    if (isset($lang)) {
        return app()->islocale($lang);
    } else {
        return app()->getlocale();
    }
}


function settings()
{
    return Setting::all();
}

function referenceTypes()
{
//    Cache::forget('reference_types');

    return Cache::rememberForever('reference_types', function () {
        return RefType::query()->select('id', 'title')->get();
    });
}

function countries()
{
    if (! Config::get('countries')) {
        Config::set('countries', Country::Active()->get());
    }

    return Config::get('countries');
}

function country($id = NULL)
{
    $id = $id ?? Config::get('country', 1);

    // Try to find the country by the given ID
    $country = countries()->where('id', $id)->first();

    // If the country does not exist, default to id = 1
    if (!$country) {
        $country = countries()->where('id', 1)->first();
    }

    return $country;
}



if (!function_exists('getAllCompanies')) {
    function getAllCompanies()
    {
        return Company::select('id', 'name','active')->get();
    }
}


if (!function_exists('getActiveCompany')) { 
    function getActiveCompany()
    {
       $authed = auth()->guard('admin')->user();

        if (!$authed) {
            // During seeding, there is no authenticated user
            return null;
        }

        if ($authed->role_id == 1) {
            return getAllCompanies()->where('active', 1)->first();
        }
        else{
            return $authed->company;
        }

    }
}

function setting($key)
{
    return Settings()->where('key', $key)->first()?->value;
}



if (! function_exists('hasAnyPermission')) {
    function hasAnyPermission($permissions) {
        $user = auth()->user();
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('permissions')) {
    function permissions($key = 'permissions', $default = null)
    {
        $permissions = config("permissions.$key", $default);

        return collect($permissions)->mapWithKeys(function ($value, $key) {
            return [$key => trns($value)];
        })->toArray();
    }
}



if (!function_exists('alignOppsite')) {
    function alignOppsite()
    {
        return lang() === 'ar' ? 'right' : 'left';
    }
}
