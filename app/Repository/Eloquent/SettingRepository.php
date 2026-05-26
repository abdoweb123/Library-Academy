<?php

namespace App\Repository\Eloquent;

use App\Http\Traits\FileManager;
use App\Models\Setting;
use App\Repository\SettingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SettingRepository extends Repository implements SettingRepositoryInterface
{
    use FileManager;

    protected Model $model;

    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }


    public function edit($type = null)
    {
//        return Route::currentRouteName();

        $Models = $this->model->when($type, function ($query, $type) {
            return $query->where('type', $type);
        })->get();

        return view('dashboard.settings.edit', compact('Models', 'type'));
    }


    /*** Update the specified resource in storage. ***/
    public function update($request)
    {
        $settingsToUpdate = collect($request->except('_token', '_method')); // Exclude Laravel's default form fields

        $settingsToUpdate->each(function ($value, $key) use ($request){
            $setting = Setting::where('key', $key)->first(); // Retrieve the setting by key

            if ($setting && $setting->input_type == 'file') {
                // Handle image file upload
                $this->deleteFile($setting->value); // Delete old file
                $value = $this->upload($key, 'Settings'); // Upload new file and get its path
            }

            // Update setting in the database
            Setting::where('key', $key)->update(['value' => $value]);
        });

//        return response()->json(['message' => 'Settings updated successfully']);

        session()->flash('success', trns('updated_successfully'));
        return redirect()->back();
    }


    public function changeLanguage($lang)
    {
        if (in_array($lang, ['en', 'ar'])) {
            session()->put('locale', $lang);

            $url = LaravelLocalization::getLocalizedURL($lang, url()->previous());

            return redirect($url);
        }

        return redirect()->back();
    }

} //end of class
