<?php

namespace App\Http\Middleware;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Stevebauman\Location\Facades\Location;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $ip = env('APP_ENV') === 'production' ? $request->ip() : '206.84.189.26';
        $country_code = Location::get($ip);

        $data = Country::where('code', $country_code->countryCode ?? "PK")->first();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => Auth::user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => fn () => session('message'),
                'error' => fn () => session('error')
            ],
            'country' => compact('data'),
        ]);
    }
}
