<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCandidatesController extends Controller
{
    public function index()
    {
//        if (Auth::id()) {
            return view('profile-candidates.index');
//        }

//        return redirect()->route('profile-candidates.login');
    }

    public function profile(Request $request)
    {
        return view('profile-candidates.profile');
    }

    public function show(Request $request)
    {
        return view('profile-candidates.show');
    }

    public function settings(Request $request)
    {
        return view('profile-candidates.settings');
    }
}
