<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileCandidatesController extends Controller
{
    public function index()
    {
        return view('profile-candidates.index');
    }
}
