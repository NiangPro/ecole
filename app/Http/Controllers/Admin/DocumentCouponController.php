<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentCoupon;
use Illuminate\Http\Request;

class DocumentCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = DocumentCoupon::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.documents.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documents = \App\Models\Document::published()->active()->get();
        $categories = \App\Models\DocumentCategory::active()->get();
        return view('admin.documents.coupons.create', compact('documents', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:document_coupons,code',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'document_id' => 'nullable|exists:documents,id',
            'category_id' => 'nullable|exists:document_categories,id',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'nullable|integer|min:1|default:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DocumentCoupon::create($validated);

        return redirect()->route('admin.documents.coupons.index')
            ->with('success', 'Code promo créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = DocumentCoupon::findOrFail($id);
        return view('admin.documents.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = DocumentCoupon::findOrFail($id);
        $documents = \App\Models\Document::published()->active()->get();
        $categories = \App\Models\DocumentCategory::active()->get();
        return view('admin.documents.coupons.edit', compact('coupon', 'documents', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = DocumentCoupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:document_coupons,code,' . $id,
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'document_id' => 'nullable|exists:documents,id',
            'category_id' => 'nullable|exists:document_categories,id',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.documents.coupons.index')
            ->with('success', 'Code promo mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = DocumentCoupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.documents.coupons.index')
            ->with('success', 'Code promo supprimé avec succès.');
    }
}
