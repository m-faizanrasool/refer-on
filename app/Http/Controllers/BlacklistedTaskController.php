<?php

namespace App\Http\Controllers;

use App\Models\BlacklistedTasks;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Str;

class BlacklistedTaskController extends Controller
{
    public function index(Request $request)
    {
        $query = BlacklistedTasks::with('country')->orderByDesc('id');

        if ($request->search) {
            $query->where(function ($innerQuery) use ($request) {
                $innerQuery->where('code', 'like', "%{$request->search}%")
                    ->orWhere('brand_name', 'like', "%{$request->search}%")
                    ->orWhereHas('country', function ($countryQuery) use ($request) {
                        $countryQuery->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        $blacklistedTasks = $query->paginate(2);

        return Inertia::render('BlacklistedTask/Index', [
            'blacklistedTasks' => $blacklistedTasks,
        ]);
    }

    public function create()
    {
        $countries = Country::all();
        return Inertia::render('BlacklistedTask/Create', [
            'countries' => $countries,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
            'code' => ['required',
                Rule::unique('blacklisted_tasks', 'code')->where(function ($query) use ($request) {
                    return $query->where('country_id', $request->country_id)
                                  ->where('brand_key', Str::lower(str_replace(' ', '_', $request->brand)));
                })],
            'brand' => 'required',
            'country_id' => 'required',
        ],
            [
                'code.unique' => 'This code is already blacklisted for this brand and country',
            ]
        );

        BlacklistedTasks::create([
            'code' => $validatedData['code'],
            'brand_name' => $validatedData['brand'],
            'brand_key' => Str::lower(str_replace(' ', '_', $validatedData['brand'])),
            'country_id' => $validatedData['country_id'],
        ]);

        return redirect()->route('blacklisted-tasks.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $blacklistedTask = BlacklistedTasks::findOrFail($id);

        $countries = Country::all();

        return Inertia::render('BlacklistedTask/Edit', [
            'blacklistedTask' => $blacklistedTask,
            'countries' => $countries,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate(
            ['code' => ['required',
                Rule::unique('blacklisted_tasks', 'code')->where(function ($query) use ($request) {
                    return $query->where('country_id', $request->country_id)
                             ->where('brand_key', Str::lower(str_replace(' ', '_', $request->brand)));
                })->ignore($id)
            ],
            'brand' => 'required',
            'country_id' => 'required',
        ],
            [
                'code.unique' => 'This code is already blacklisted for this country',
            ]
        );

        $blacklistedTask = BlacklistedTasks::find($id);

        $blacklistedTask->update([
            'code' => $validatedData['code'],
            'brand_name' => $validatedData['brand'],
            'brand_key' => Str::lower(str_replace(' ', '_', $validatedData['brand'])),
            'country_id' => $validatedData['country_id'],
        ]);

        return redirect()->route('blacklisted-tasks.index');
    }

    public function destroy($id)
    {
        $blacklistedTask = BlacklistedTasks::findOrFail($id);

        $blacklistedTask->delete();
    }
}
