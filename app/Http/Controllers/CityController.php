<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class CityController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $cities = City::all();

        return view('index', compact('cities'));
    }
}
