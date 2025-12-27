<?php

namespace App\Http\Controllers;

use App\Models\DocumentBundle;
use App\Http\Controllers\Concerns\LocaleTrait;
use Illuminate\Http\Request;

class DocumentBundleController extends Controller
{
    use LocaleTrait;

    /**
     * Afficher la liste des bundles
     */
    public function index()
    {
        $this->ensureLocale();
        
        $bundles = DocumentBundle::active()
            ->with(['items.document.category'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('documents.bundles.index', compact('bundles'));
    }

    /**
     * Afficher les détails d'un bundle
     */
    public function show($slug)
    {
        $this->ensureLocale();
        
        $bundle = DocumentBundle::where('slug', $slug)
            ->active()
            ->with(['items.document.category', 'items.document.author'])
            ->firstOrFail();

        // Documents similaires (autres bundles)
        $relatedBundles = DocumentBundle::active()
            ->where('id', '!=', $bundle->id)
            ->orderBy('sales_count', 'desc')
            ->take(4)
            ->get();

        return view('documents.bundles.show', compact('bundle', 'relatedBundles'));
    }
}
