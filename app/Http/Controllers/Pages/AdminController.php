<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('sortParam')) {
            switch ($request->sortParam) {
                case 'date_now':
                    $journals = Journal::orderBy('created_at', 'desc')->paginate(10);
                    break;
                case 'date_old':
                    $journals = Journal::orderBy('created_at', 'asc')->paginate(10);
                    break;
                case 'statuses':
                    $journals = Journal::orderByRaw(
                        "FIELD(status, 'Загружено', 'Частично загружено', 'В обработке', 'Ошибка загрузки')"
                    )->paginate(10);
                    break;
            }
        } else {
            $journals = Journal::paginate(10);
        }

        return view('admin.home', compact('journals'));
    }
}
