<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $country_code = Auth::user()->country->code;
        } else {
            $ip = env('APP_ENV') === 'production' ? $request->ip() : '206.84.189.26';
            $country = Location::get($ip);

            $country_code = $country->countryCode;
        }

        $brands = Brand::whereHas('country', function ($country) use ($country_code) {
            $country->where('code', $country_code);
        })->get()->append('logo_url');

        $quereyParam = $request->input('search');

        $availableTasks = [];

        if (!empty($quereyParam) && Auth::user()) {
            $availableTasks = TaskService::search($quereyParam);
        }

        return Inertia::render('Home', compact('availableTasks', 'quereyParam', 'brands'));
    }
}
