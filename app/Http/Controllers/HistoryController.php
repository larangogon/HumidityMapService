<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HistoryController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $histories = History::with('city')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('history', compact('histories'));
    }
}
