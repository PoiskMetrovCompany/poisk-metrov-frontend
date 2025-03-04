<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {

    }


    public function indexPage(Request $request)
    {
        return view('reservation.index');
    }
}
