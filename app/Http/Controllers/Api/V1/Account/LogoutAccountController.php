<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutAccountController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Auth::logout();
            $request->session()->regenerate();
            return response()->json(['status' => 'Log out success'], 200);
        } else {
            return response()->json(['status' => 'User not logged in'], 500);
        }
    }
}
