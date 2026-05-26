<?php

namespace App\Http\Services\Dashboard\Auth;

use App\Http\Requests\Dashboard\Auth\LoginRequest;

class AuthService
{
    public function login(LoginRequest $request){
        $credentials = $request->validated();
        $rememberMe = $request->remember_me == 'on';
        if (auth('admin')->attempt($credentials, $rememberMe)) {
            return redirect()->route('dashboard.dashboard');
        } else {
            return redirect()->route('auth.login')->with(['error' => __('messages.Incorrect email or password')]);
        }
    }

    public function logout(){
        auth('admin')->logout();
        return redirect()->route('auth.login');
    }

}
