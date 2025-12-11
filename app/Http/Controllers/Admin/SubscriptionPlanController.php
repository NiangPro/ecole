<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    /**
     * Afficher la liste des plans
     */
    public function index()
    {
        $plans = SubscriptionPlan::ordered()->get();
        
        return view('admin.monetization.subscription-plans.index', compact('plans'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.monetization.subscription-plans.create');
    }

    /**
     * Enregistrer un nouveau plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'billing_period' => 'required|in:monthly,yearly',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
        ], [
            'name.required' => 'Le nom du plan est obligatoire.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.',
            'billing_period.required' => 'La période de facturation est obligatoire.',
            'billing_period.in' => 'La période de facturation doit être mensuelle ou annuelle.',
            'duration_days.required' => 'La durée en jours est obligatoire.',
            'duration_days.integer' => 'La durée doit être un nombre entier.',
            'duration_days.min' => 'La durée doit être supérieure à 0.',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Vérifier l'unicité
            $count = 1;
            $originalSlug = $validated['slug'];
            while (SubscriptionPlan::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        // Convertir les checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order'] = $validated['order'] ?? 0;

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.monetization.subscription-plans.index')
            ->with('success', 'Plan d\'abonnement créé avec succès.');
    }

    /**
     * Afficher un plan
     */
    public function show($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        return view('admin.monetization.subscription-plans.show', compact('plan'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        return view('admin.monetization.subscription-plans.edit', compact('plan'));
    }

    /**
     * Mettre à jour un plan
     */
    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:subscription_plans,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'billing_period' => 'required|in:monthly,yearly',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
        ], [
            'name.required' => 'Le nom du plan est obligatoire.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.',
            'billing_period.required' => 'La période de facturation est obligatoire.',
            'billing_period.in' => 'La période de facturation doit être mensuelle ou annuelle.',
            'duration_days.required' => 'La durée en jours est obligatoire.',
            'duration_days.integer' => 'La durée doit être un nombre entier.',
            'duration_days.min' => 'La durée doit être supérieure à 0.',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Vérifier l'unicité
            $count = 1;
            $originalSlug = $validated['slug'];
            while (SubscriptionPlan::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        // Convertir les checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order'] = $validated['order'] ?? 0;

        $plan->update($validated);

        return redirect()->route('admin.monetization.subscription-plans.index')
            ->with('success', 'Plan d\'abonnement mis à jour avec succès.');
    }

    /**
     * Supprimer un plan
     */
    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        // Vérifier s'il y a des abonnements actifs avec ce plan
        $activeSubscriptions = $plan->subscriptions()->where('status', 'active')->count();
        
        if ($activeSubscriptions > 0) {
            return redirect()->route('admin.monetization.subscription-plans.index')
                ->with('error', 'Impossible de supprimer ce plan car il y a ' . $activeSubscriptions . ' abonnement(s) actif(s) associé(s).');
        }
        
        $plan->delete();

        return redirect()->route('admin.monetization.subscription-plans.index')
            ->with('success', 'Plan d\'abonnement supprimé avec succès.');
    }
}
