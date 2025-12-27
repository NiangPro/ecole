<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\LocaleTrait;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentPurchase;
use App\Models\DocumentDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    use LocaleTrait;

    /**
     * Afficher la liste des documents
     */
    public function index(Request $request)
    {
        $this->ensureLocale();
        
        $query = Document::published()->active()->with(['category', 'author']);

        // Filtres
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('published_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('sales_count', 'desc');
                    break;
                default:
                    $query->orderBy('published_at', 'desc');
            }
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $documents = $query->paginate(12);
        $categories = DocumentCategory::active()->ordered()->get();
        $featuredDocuments = Document::published()->active()->featured()
            ->with(['category'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        return view('documents.index', compact('documents', 'categories', 'featuredDocuments'));
    }

    /**
     * Afficher les documents d'une catégorie
     */
    public function category($slug)
    {
        $this->ensureLocale();
        
        $category = DocumentCategory::where('slug', $slug)->active()->firstOrFail();
        
        $query = Document::published()->active()
            ->where('category_id', $category->id)
            ->with(['category', 'author']);

        $documents = $query->orderBy('published_at', 'desc')->paginate(12);
        $categories = DocumentCategory::active()->ordered()->get();

        return view('documents.category', compact('category', 'documents', 'categories'));
    }

    /**
     * Afficher les détails d'un document
     */
    public function show($slug)
    {
        $this->ensureLocale();
        
        $document = Document::where('slug', $slug)
            ->published()
            ->active()
            ->with(['category', 'author'])
            ->firstOrFail();

        // Incrémenter le compteur de vues
        $document->increment('views_count');

        // 4 derniers documents (exclure le document actuel)
        $relatedDocuments = Document::published()
            ->active()
            ->where('id', '!=', $document->id)
            ->with(['category'])
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // Vérifier si l'utilisateur a déjà acheté ce document
        $userHasPurchased = false;
        if (auth()->check()) {
            $userHasPurchased = $document->completedPurchases()
                ->where('user_id', auth()->id())
                ->exists();
        }

        // Récupérer les avis approuvés
        $reviews = $document->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Vérifier si dans wishlist
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = \App\Models\DocumentWishlist::isInWishlist(
                auth()->id(), 
                $document->id
            );
        }

        // Recommandations personnalisées
        $recommendations = $this->getPersonalizedRecommendations($document);

        return view('documents.show', compact(
            'document', 
            'relatedDocuments', 
            'userHasPurchased',
            'reviews',
            'inWishlist',
            'recommendations'
        ));
    }

    /**
     * Télécharger un document par token (pour visiteurs anonymes)
     */
    public function downloadByToken(Request $request, $token)
    {
        $this->ensureLocale();
        
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $purchase = DocumentPurchase::where('download_token', $token)
            ->where(function($query) use ($request) {
                $query->where('customer_email', $request->email)
                      ->orWhereHas('user', function($q) use ($request) {
                          $q->where('email', $request->email);
                      });
            })
            ->first();
        
        if (!$purchase) {
            return redirect()->route('documents.index')
                ->with('error', 'Lien de téléchargement invalide ou expiré.');
        }
        
        // Vérifier le token
        if (!$purchase->isTokenValid($token)) {
            return redirect()->route('documents.index')
                ->with('error', 'Ce lien de téléchargement a expiré.');
        }
        
        // Vérifier si le téléchargement est autorisé
        if (!$purchase->canDownload()) {
            return redirect()->route('documents.index')
                ->with('error', 'Vous avez atteint la limite de téléchargements pour ce document.');
        }
        
        // Enregistrer le téléchargement
        DocumentDownload::create([
            'purchase_id' => $purchase->id,
            'user_id' => $purchase->user_id,
            'document_id' => $purchase->document_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'downloaded_at' => now(),
        ]);
        
        // Incrémenter le compteur
        $purchase->incrementDownloadCount();
        $purchase->document->increment('download_count');
        
        // Obtenir le fichier
        $filePath = $purchase->document->file_path;
        
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($filePath)) {
            return redirect()->route('documents.index')
                ->with('error', 'Le fichier n\'existe plus.');
        }
        
        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $filePath,
            $purchase->document->file_name
        );
    }

    /**
     * Afficher les documents achetés par l'utilisateur connecté
     */
    public function myDocuments(Request $request)
    {
        $this->ensureLocale();
        
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à vos documents.');
        }
        
        $user = Auth::user();
        
        $purchases = DocumentPurchase::with(['document.category', 'downloads'])
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->where('status', 'completed')
            ->orderBy('purchased_at', 'desc')
            ->paginate(12);
        
        // Utiliser la vue dashboard si la route est dans le dashboard
        if (request()->routeIs('dashboard.my-documents')) {
            return view('dashboard.my-documents', compact('purchases'));
        }
        
        return view('documents.my-documents', compact('purchases'));
    }

    /**
     * Afficher les documents achetés par email (pour visiteurs anonymes)
     */
    public function myDocumentsByEmail(Request $request)
    {
        $this->ensureLocale();
        
        $email = $request->input('email');
        
        if (!$email) {
            return view('documents.my-documents-email', ['purchases' => collect()]);
        }
        
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $purchases = DocumentPurchase::where(function($query) use ($email) {
                $query->where('customer_email', $email)
                      ->orWhereHas('user', function($q) use ($email) {
                          $q->where('email', $email);
                      });
            })
            ->completed()
            ->with(['document.category', 'payment'])
            ->orderBy('purchased_at', 'desc')
            ->get();
        
        return view('documents.my-documents-email', compact('purchases', 'email'));
    }

    /**
     * Télécharger un document gratuit directement
     */
    public function downloadFree($id)
    {
        $this->ensureLocale();
        
        $document = Document::published()->active()->findOrFail($id);
        
        if (!$document->isFree()) {
            abort(403, 'Ce document n\'est pas gratuit.');
        }
        
        // Vérifier que le fichier existe
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path)) {
            return redirect()->route('documents.show', $document->slug)
                ->with('error', 'Le fichier n\'existe plus.');
        }
        
        // Enregistrer le téléchargement
        $document->increment('download_count');
        
        // Enregistrer dans DocumentDownload si utilisateur connecté
        if (Auth::check()) {
            DocumentDownload::create([
                'user_id' => Auth::id(),
                'document_id' => $document->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'downloaded_at' => now(),
            ]);
        }
        
        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $document->file_path,
            $document->file_name
        );
    }

    /**
     * Obtenir des recommandations personnalisées pour un document
     */
    private function getPersonalizedRecommendations(Document $document)
    {
        $user = Auth::user();
        
        if (!$user) {
            // Recommandations basées sur la catégorie pour visiteurs anonymes
            return Document::published()
                ->active()
                ->where('category_id', $document->category_id)
                ->where('id', '!=', $document->id)
                ->where('is_free', false) // Exclure les gratuits pour recommandations
                ->orderBy('sales_count', 'desc')
                ->orderBy('views_count', 'desc')
                ->take(6)
                ->get();
        }
        
        // Basées sur les achats précédents
        $purchasedCategories = DocumentPurchase::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('document.category')
            ->get()
            ->pluck('document.category_id')
            ->filter()
            ->unique();
        
        // Basées sur les catégories des documents consultés (via vues)
        $viewedDocuments = Document::whereHas('purchases', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->orWhereHas('wishlists', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->pluck('category_id')
        ->filter()
        ->unique();
        
        $categories = $purchasedCategories->merge($viewedDocuments)->unique();
        
        if ($categories->isEmpty()) {
            // Fallback: catégorie du document actuel
            $categories = collect([$document->category_id]);
        }
        
        return Document::published()
            ->active()
            ->whereIn('category_id', $categories)
            ->where('id', '!=', $document->id)
            ->where('is_free', false)
            ->orderBy('sales_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();
    }
}
