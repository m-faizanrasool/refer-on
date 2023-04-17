<?php

namespace App\Http\Controllers;

use App\Models\BlacklistedTasks;
use App\Models\Brand;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BlacklistedTaskController extends Controller
{
    public function index()
    {
        $blacklistedTask = BlacklistedTasks::with(['brand', 'country'])->get();

        return Inertia::render('BlacklistedTask/Index', [
            'blacklistedTask' => $blacklistedTask,
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
        $request->validate(
            [
            'key' => ['required',
                Rule::unique('blacklisted_tasks', 'key')->where(function ($query) use ($request) {
                    return $query->where('country_id', $request->country_id);
                })],
            'brand' => 'required',
            'country_id' => 'required',
        ],
            [
                'key.unique' => 'This task is already blacklisted for this country',
            ]
        );

        $brand = Brand::where('name', $request->brand)->first();

        if (!$brand) {
            $brand = Brand::create(['name' => $request->brand]);
        }

        BlacklistedTasks::create([
            'key' => $request->key,
            'brand_id' => $brand->id,
            'country_id' => $request->country_id
        ]);

        return redirect()->route('blacklisted-tasks.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $blacklistedTask = BlacklistedTasks::with('brand')->find($id);

        $countries = Country::all();

        return Inertia::render('BlacklistedTask/Edit', [
            'blacklistedTask' => $blacklistedTask,
            'countries' => $countries,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
            'key' => ['required',
                Rule::unique('blacklisted_tasks', 'key')->where(function ($query) use ($request) {
                    return $query->where('country_id', $request->country_id);
                })],
            'brand' => 'required',
            'country_id' => 'required',
        ],
            [
                'key.unique' => 'This task is already blacklisted for this country',
            ]
        );

        $brand = Brand::where('name', $request->brand)->first();

        if (!$brand) {
            $brand = Brand::create(['name' => $request->brand]);
        }

        $blacklistedTask = BlacklistedTasks::find($id);

        $blacklistedTask->update([
            'key' => $request->key,
            'brand_id' => $brand->id,
            'country_id' => $request->country_id
        ]);

        return redirect()->route('blacklisted-tasks.index');
    }

    public function destroy($id)
    {
        $blacklistedTask = BlacklistedTasks::findOrFail($id);

        $blacklistedTask->delete();
    }
}
