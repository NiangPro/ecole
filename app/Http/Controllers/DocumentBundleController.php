<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentBundle;
use App\Models\DocumentCart;
use App\Models\DocumentPurchase;
use App\Models\Epreuve;
use App\Http\Controllers\Concerns\LocaleTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentBundleController extends Controller
{
    use LocaleTrait;

    /**
     * Charge les items d'un pack avec leur contenu polymorphe (Document ou Epreuve).
     */
    private function withItemables()
    {
        return function ($query) {
            $query->with(['itemable' => function ($morphTo) {
                $morphTo->morphWith([
                    Document::class => ['category', 'author'],
                    Epreuve::class => ['matiere'],
                ]);
            }]);
        };
    }

    /**
     * Afficher la liste des bundles
     */
    public function index()
    {
        $this->ensureLocale();

        $bundles = DocumentBundle::active()
            ->with(['items' => $this->withItemables()])
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
            ->with(['items' => $this->withItemables()])
            ->firstOrFail();

        // Documents similaires (autres bundles)
        $relatedBundles = DocumentBundle::active()
            ->where('id', '!=', $bundle->id)
            ->orderBy('sales_count', 'desc')
            ->take(4)
            ->get();

        return view('documents.bundles.show', compact('bundle', 'relatedBundles'));
    }

    /**
     * Ajouter tous les documents d'un pack au panier, au prix réduit du pack
     */
    public function addToCart($slug)
    {
        $this->ensureLocale();

        $bundle = DocumentBundle::where('slug', $slug)
            ->active()
            ->with('items')
            ->firstOrFail();

        // L'achat groupé n'est pas encore disponible pour les packs contenant des épreuves
        // (flux d'achat totalement séparé, pas de panier commun pour l'instant).
        $hasEpreuveItem = $bundle->items->contains('item_type', Epreuve::class);
        if ($hasEpreuveItem) {
            return redirect()->back()
                ->with('error', 'Ce pack contient des épreuves : l\'achat groupé n\'est pas encore disponible pour ce type de contenu.');
        }

        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : session()->getId();

        $totalIndividualPrice = $bundle->total_individual_price;
        $bundlePrice = $bundle->current_price;
        $added = 0;

        foreach ($bundle->items as $item) {
            $document = $item->item_type === Document::class ? $item->itemable : null;

            if (!$document || !$document->is_active || $document->status !== 'published') {
                continue;
            }

            if (Auth::check()) {
                $alreadyPurchased = DocumentPurchase::where('user_id', Auth::id())
                    ->where('document_id', $document->id)
                    ->where('status', 'completed')
                    ->exists();

                if ($alreadyPurchased) {
                    continue;
                }
            }

            $existing = $userId
                ? DocumentCart::forUser($userId)->where('document_id', $document->id)->first()
                : DocumentCart::forSession($sessionId)->where('document_id', $document->id)->first();

            if ($existing) {
                continue;
            }

            $docIndividualPrice = $document->discount_price ?? $document->price;
            $splitPrice = $totalIndividualPrice > 0
                ? round(($docIndividualPrice / $totalIndividualPrice) * $bundlePrice, 2)
                : 0;

            DocumentCart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'document_id' => $document->id,
                'bundle_id' => $bundle->id,
                'quantity' => 1,
                'price' => $splitPrice,
            ]);

            $added++;
        }

        if ($added === 0) {
            return redirect()->back()
                ->with('info', 'Les documents de ce pack sont déjà dans votre panier ou déjà achetés.');
        }

        return redirect()->route('documents.checkout.payment')
            ->with('success', 'Pack ajouté au panier avec succès.');
    }
}
