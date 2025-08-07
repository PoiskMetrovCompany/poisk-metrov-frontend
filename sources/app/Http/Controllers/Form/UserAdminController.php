<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public function login(Request $request)
    {

        $user = User::where('name', $request->input('admin-login'))->first();
        if (Hash::check($request->input('admin-password'), $user->password)) {
            Auth::login($user);
        }
        return redirect()->route('admin.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
