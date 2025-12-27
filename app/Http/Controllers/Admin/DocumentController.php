<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentPurchase;
use App\Models\DocumentDownload;
use App\Rules\ValidDocumentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DocumentController extends Controller
{
    use LocaleTrait;

    public function index(Request $request)
    {
        $this->ensureLocale();
        $query = Document::with(['category', 'author']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = DocumentCategory::active()->ordered()->get();

        return view('admin.documents.index', compact('documents', 'categories'));
    }

    public function create()
    {
        $this->ensureLocale();
        $categories = DocumentCategory::active()->ordered()->get();
        return view('admin.documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->ensureLocale();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:documents,slug',
            'description' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:document_categories,id',
            'file' => ['required', 'file', new ValidDocumentFile(), 'max:102400'], // 100MB max
            'cover_image' => 'nullable|string',
            'cover_type' => 'required|in:internal,external',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Document::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Upload du fichier document
        $file = $request->file('file');
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents', $fileName, 'local');
        
        $validated['file_path'] = $filePath;
        $validated['file_name'] = $file->getClientOriginalName();
        $validated['file_size'] = $file->getSize();
        $validated['file_type'] = $file->getMimeType();
        $validated['file_extension'] = $file->getClientOriginalExtension();
        $validated['author_id'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Gérer les tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = array_filter($tags);
        }

        // Gérer l'upload de l'image de couverture
        if ($validated['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
            $image = $request->file('cover_image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('document-covers', $imageName, 'public');
            $validated['cover_image'] = $imagePath;
        } elseif ($validated['cover_type'] === 'external' && $request->has('cover_image_url')) {
            $url = trim($request->input('cover_image_url', ''));
            if (!empty($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
                $validated['cover_image'] = $url;
            }
        }

        // Définir published_at si publié
        if ($validated['status'] === 'published' && !$request->has('published_at')) {
            $validated['published_at'] = now();
        }

        $document = Document::create($validated);

        // Notifier les utilisateurs si le document est publié
        if ($validated['status'] === 'published') {
            \App\Services\NotificationService::notifyNewDocument($document);
        }

        return redirect()->route('admin.documents.documents.show', $document->id)
            ->with('success', 'Document créé avec succès');
    }

    public function show($id)
    {
        $this->ensureLocale();
        $document = Document::with(['category', 'author', 'purchases'])
            ->withCount('purchases', 'completedPurchases')
            ->findOrFail($id);
        
        return view('admin.documents.show', compact('document'));
    }

    public function edit($id)
    {
        $this->ensureLocale();
        $document = Document::findOrFail($id);
        $categories = DocumentCategory::active()->ordered()->get();
        return view('admin.documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureLocale();
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:documents,slug,' . $id,
            'description' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:document_categories,id',
            'file' => ['nullable', 'file', new ValidDocumentFile(), 'max:102400'],
            'cover_image' => 'nullable|string',
            'cover_type' => 'required|in:internal,external',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Document::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Upload d'un nouveau fichier si fourni
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if (Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents', $fileName, 'local');
            
            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getMimeType();
            $validated['file_extension'] = $file->getClientOriginalExtension();
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Gérer les tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = array_filter($tags);
        }

        // Gérer l'upload de l'image de couverture
        if ($validated['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
            // Supprimer l'ancienne image si elle existe
            if ($document->cover_image && $document->cover_type === 'internal' && Storage::disk('public')->exists($document->cover_image)) {
                Storage::disk('public')->delete($document->cover_image);
            }
            $image = $request->file('cover_image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('document-covers', $imageName, 'public');
            $validated['cover_image'] = $imagePath;
        } elseif ($validated['cover_type'] === 'external' && $request->has('cover_image_url')) {
            $url = trim($request->input('cover_image_url', ''));
            if (!empty($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
                $validated['cover_image'] = $url;
            }
        }

        // Définir published_at si publié
        $wasPublished = $document->status === 'published';
        $isNowPublished = $validated['status'] === 'published' && !$document->published_at;
        
        if ($isNowPublished) {
            $validated['published_at'] = now();
        }

        // Vérifier si une réduction a été ajoutée
        $hadDiscount = $document->discount_price && $document->discount_price < $document->price;
        $hasNewDiscount = isset($validated['discount_price']) && 
                          $validated['discount_price'] && 
                          $validated['discount_price'] < $validated['price'] &&
                          (!$hadDiscount || $validated['discount_price'] < $document->discount_price);

        $oldPrice = $document->price;
        $oldDiscountPrice = $document->discount_price;

        $document->update($validated);

        // Notifier si le document vient d'être publié
        if ($isNowPublished && !$wasPublished) {
            \App\Services\NotificationService::notifyNewDocument($document);
        }

        // Notifier si une réduction a été ajoutée
        if ($hasNewDiscount) {
            \App\Services\NotificationService::notifyDiscount(
                $document,
                $oldDiscountPrice ?: $oldPrice,
                $validated['discount_price']
            );
        }

        return redirect()->route('admin.documents.documents.show', $document->id)
            ->with('success', 'Document mis à jour avec succès');
    }

    public function destroy($id)
    {
        $this->ensureLocale();
        $document = Document::findOrFail($id);

        // Vérifier s'il y a des achats
        if ($document->purchases()->where('status', 'completed')->count() > 0) {
            return redirect()->route('admin.documents.documents.index')
                ->with('error', 'Impossible de supprimer ce document car il a été acheté.');
        }

        // Supprimer le fichier
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        // Supprimer l'image de couverture
        if ($document->cover_image && $document->cover_type === 'internal' && Storage::disk('public')->exists($document->cover_image)) {
            Storage::disk('public')->delete($document->cover_image);
        }

        $document->delete();

        return redirect()->route('admin.documents.documents.index')
            ->with('success', 'Document supprimé avec succès');
    }

    public function publish($id)
    {
        $this->ensureLocale();
        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return redirect()->back()->with('success', 'Document publié avec succès');
    }

    public function unpublish($id)
    {
        $this->ensureLocale();
        $document = Document::findOrFail($id);
        $document->update(['status' => 'draft']);

        return redirect()->back()->with('success', 'Document dépublié avec succès');
    }

    /**
     * Afficher les statistiques des documents
     */
    public function statistics(Request $request)
    {
        $this->ensureLocale();
        
        $filter = $request->get('filter', 'month');
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        // Statistiques générales
        $stats = [
            'total_documents' => Document::count(),
            'published_documents' => Document::where('status', 'published')->count(),
            'total_sales' => DocumentPurchase::where('status', 'completed')->count(),
            'total_revenue' => DocumentPurchase::where('status', 'completed')->sum('amount_paid'),
            'pending_purchases' => DocumentPurchase::where('status', 'pending')->count(),
            'total_downloads' => DocumentDownload::count(),
            'total_views' => Document::sum('views_count'),
        ];
        
        // Revenus par période
        $revenueByPeriod = [];
        if ($filter === 'day') {
            $day = $request->get('day', Carbon::now()->day);
            $revenueByPeriod = DocumentPurchase::where('status', 'completed')
                ->whereDate('purchased_at', Carbon::createFromDate($year, $month, $day))
                ->selectRaw('HOUR(purchased_at) as period, SUM(amount_paid) as total, COUNT(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } elseif ($filter === 'month') {
            $revenueByPeriod = DocumentPurchase::where('status', 'completed')
                ->whereYear('purchased_at', $year)
                ->whereMonth('purchased_at', $month)
                ->selectRaw('DAY(purchased_at) as period, SUM(amount_paid) as total, COUNT(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } else {
            $revenueByPeriod = DocumentPurchase::where('status', 'completed')
                ->whereYear('purchased_at', $year)
                ->selectRaw('MONTH(purchased_at) as period, SUM(amount_paid) as total, COUNT(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }
        
        // Top 10 documents les plus vendus
        $topDocuments = Document::with('category')
            ->whereHas('purchases', function($query) {
                $query->where('status', 'completed');
            })
            ->withCount(['purchases' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('purchases_count', 'desc')
            ->take(10)
            ->get();
        
        // Revenus par catégorie
        $revenueByCategory = DocumentCategory::withCount(['documents' => function($query) {
                $query->where('status', 'published');
            }])
            ->with(['documents' => function($query) {
                $query->whereHas('purchases', function($q) {
                    $q->where('status', 'completed');
                });
            }])
            ->get()
            ->map(function($category) {
                $revenue = $category->documents->sum(function($doc) {
                    return $doc->purchases()->where('status', 'completed')->sum('amount_paid');
                });
                $sales = $category->documents->sum(function($doc) {
                    return $doc->purchases()->where('status', 'completed')->count();
                });
                return [
                    'name' => $category->name,
                    'revenue' => $revenue,
                    'sales' => $sales,
                ];
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values();
        
        // Statistiques de téléchargements
        $downloadsStats = [
            'total' => DocumentDownload::count(),
            'this_month' => DocumentDownload::whereMonth('downloaded_at', Carbon::now()->month)
                ->whereYear('downloaded_at', Carbon::now()->year)
                ->count(),
            'this_week' => DocumentDownload::whereBetween('downloaded_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'today' => DocumentDownload::whereDate('downloaded_at', Carbon::today())->count(),
        ];
        
        // Taux de conversion (vues → achats)
        $conversionRate = $stats['total_views'] > 0 
            ? round(($stats['total_sales'] / $stats['total_views']) * 100, 2)
            : 0;
        
        // Revenus par mois (12 derniers mois)
        $revenueByMonth = DocumentPurchase::where('status', 'completed')
            ->whereNotNull('purchased_at')
            ->where('purchased_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(purchased_at, "%Y-%m") as month, SUM(amount_paid) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Années disponibles
        $availableYears = DocumentPurchase::where('status', 'completed')
            ->whereNotNull('purchased_at')
            ->selectRaw('YEAR(purchased_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('admin.documents.statistics', compact(
            'stats',
            'revenueByPeriod',
            'topDocuments',
            'revenueByCategory',
            'downloadsStats',
            'conversionRate',
            'revenueByMonth',
            'filter',
            'year',
            'month',
            'availableYears'
        ));
    }
}
