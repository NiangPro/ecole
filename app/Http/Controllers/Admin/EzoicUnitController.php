<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EzoicUnit;
use Illuminate\Http\Request;

class EzoicUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = EzoicUnit::ordered()->get();
        
        return view('admin.ezoic-units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ezoic-units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'ad_id' => 'required|string|max:255',
                'ad_format' => 'required|in:auto,horizontal,vertical,rectangle',
                'position' => 'required|in:header,sidebar,content,footer,in-article',
                'location' => 'nullable|string|max:255',
                'size' => 'nullable|string|max:50',
                'status' => 'required|in:active,inactive',
                'order' => 'nullable|integer|min:0',
                'custom_code' => 'nullable|string',
            ], [
                'name.required' => 'Le nom de l\'unité est obligatoire.',
                'ad_id.required' => 'L\'ID de l\'annonce Ezoic est obligatoire.',
                'ad_format.required' => 'Le format est obligatoire.',
                'ad_format.in' => 'Le format sélectionné n\'est pas valide.',
                'position.required' => 'La position est obligatoire.',
                'position.in' => 'La position sélectionnée n\'est pas valide.',
                'status.required' => 'Le statut est obligatoire.',
                'status.in' => 'Le statut sélectionné n\'est pas valide.',
                'order.integer' => 'L\'ordre doit être un nombre entier.',
                'order.min' => 'L\'ordre doit être supérieur ou égal à 0.',
            ]);

            // Gérer la checkbox responsive manuellement
            $validated['responsive'] = $request->has('responsive');
            $validated['order'] = $validated['order'] ?? 0;

            $unit = EzoicUnit::create($validated);
            
            if (!$unit || !$unit->id) {
                throw new \Exception('Échec de la création de l\'unité Ezoic');
            }

            \Log::info('Unité Ezoic créée avec succès', [
                'id' => $unit->id,
                'name' => $unit->name,
                'ad_id' => $unit->ad_id
            ]);

            return redirect()->route('admin.ezoic-units.index')
                ->with('success', 'Unité publicitaire créée avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'unité Ezoic: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'unité. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $unit = EzoicUnit::findOrFail($id);
        
        return view('admin.ezoic-units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $unit = EzoicUnit::findOrFail($id);
        
        return view('admin.ezoic-units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unit = EzoicUnit::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ad_id' => 'required|string|max:255',
            'ad_format' => 'required|in:auto,horizontal,vertical,rectangle',
            'position' => 'required|in:header,sidebar,content,footer,in-article',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'custom_code' => 'nullable|string',
        ]);

        // Gérer la checkbox responsive manuellement
        $validated['responsive'] = $request->has('responsive');

        $unit->update($validated);

        return redirect()->route('admin.ezoic-units.index')
            ->with('success', 'Unité publicitaire mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $unit = EzoicUnit::findOrFail($id);
        $unit->delete();

        return redirect()->route('admin.ezoic-units.index')
            ->with('success', 'Unité publicitaire supprimée avec succès');
    }
}
