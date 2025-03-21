<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookedOrderFormController extends Controller
{
    final public function store(Request $request)
    {
        return response()->json($request);
    }

    final public function destroy()
    {
        return;
    }
}
