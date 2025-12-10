<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSenseUnit;
use Illuminate\Http\Request;

class AdSenseUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = AdSenseUnit::ordered()->get();
        
        return view('admin.adsense-units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.adsense-units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ad_slot' => 'required|string|max:255',
            'ad_format' => 'required|in:auto,horizontal,vertical,rectangle',
            'position' => 'required|in:header,sidebar,content,footer,in-article',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'responsive' => 'boolean',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'custom_code' => 'nullable|string',
        ]);

        $validated['responsive'] = $request->has('responsive');
        $validated['order'] = $validated['order'] ?? 0;

        AdSenseUnit::create($validated);

        return redirect()->route('admin.adsense-units.index')
            ->with('success', 'Unité publicitaire créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $unit = AdSenseUnit::findOrFail($id);
        
        return view('admin.adsense-units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $unit = AdSenseUnit::findOrFail($id);
        
        return view('admin.adsense-units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unit = AdSenseUnit::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ad_slot' => 'required|string|max:255',
            'ad_format' => 'required|in:auto,horizontal,vertical,rectangle',
            'position' => 'required|in:header,sidebar,content,footer,in-article',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'responsive' => 'boolean',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'custom_code' => 'nullable|string',
        ]);

        $validated['responsive'] = $request->has('responsive');

        $unit->update($validated);

        return redirect()->route('admin.adsense-units.index')
            ->with('success', 'Unité publicitaire mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $unit = AdSenseUnit::findOrFail($id);
        $unit->delete();

        return redirect()->route('admin.adsense-units.index')
            ->with('success', 'Unité publicitaire supprimée avec succès');
    }
}
