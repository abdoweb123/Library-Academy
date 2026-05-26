<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function index()
    {
//        return app()->currentLocale();
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard.admin.adminHome');
        }
        return view('admin.auth.login');
    }

    public function login($request)
    {
        $data = $request->validate([
            'input' => 'required',
            'password' => 'required',
        ], [
            'password.required' => trns('password_required'),
        ]);

        $admin = Admin::where('name', $data['input'])->orWhere('email', $data['input'])->first();

        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return redirect()->back()->with('error', trns('invalid_credentials'));
        }

        // Regular login
        Auth::guard('admin')->login($admin);
        return redirect()->intended(route('dashboard.admin.adminHome'));

    }


    /*** Login by JWT token ***/
    public function loginByToken($request)
    {
        $token = $request->query('token');
        $source_url = urldecode($request->query('url'));

        if (!$token) {
            return response()->json(['error' => 'Token مفقود'], 400);
        }

        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            $email = $payload->get('email');

            $admin = \App\Models\Admin::where('email', $email)->first();

            if (!$admin) {
                return redirect()->route('login')->with(['error' => 'User does not exist in database of FIN']);
//                return response()->json(['error' => 'User does not exist in database of FIN'], 404);
            }

            Auth::guard('admin')->login($admin);

            Setting::query()->where('key', 'original_app')->update([
                'value' => 0
            ]);

            Setting::query()->where('key','source_app_url')->update([
                'value' => $source_url
            ]);

            return redirect()->route('dashboard.admin.adminHome');

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
//            return response()->json(['error' => 'Token Expired'], 401);
            return redirect()->route('dashboard.admin.adminHome');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
//            return response()->json(['error' => 'Token Invalid'], 401);
            return redirect()->route('dashboard.admin.adminHome');
        } catch (\Exception $e) {
//            return response()->json(['error' => 'Error while checking token'], 500);
            return redirect()->route('dashboard.admin.adminHome');
        }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('dashboard.admin.login');
    }

}
