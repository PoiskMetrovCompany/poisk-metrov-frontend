<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedFormController extends Controller
{
    public function synchronizeFeed(Request $request)
    {
        $fileData = $request->file('file');
        $fileData->store('uploads', 'public');
    }
}
