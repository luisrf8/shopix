<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('plans', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'logo'          => 'nullable|string|max:500',
            'features'      => 'nullable|string'
        ]);

        $validated['features'] = $validated['features']
            ? json_encode(array_map('trim', explode(',', $validated['features'])))
            : json_encode([]);

        $plan = Plan::create($validated);

        return response()->json($plan, 201);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'logo'          => 'nullable|string|max:500',
            'features'      => 'nullable|string'
        ]);

        $validated['features'] = $validated['features']
            ? array_map('trim', explode(',', $validated['features']))
            : [];

        $plan->update($validated);

        return response()->json($plan);
    }


    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->noContent();
    }
}
