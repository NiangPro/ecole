<?php

namespace App\Http\Controllers;

use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class EpreuveController extends Controller
{
    /**
     * Hub Épreuves & Corrigés : liste filtrable (examen, niveau, matière, série, année).
     */
    public function index(Request $request)
    {
        $view = $this->renderListing($request, [
            'pageTitle' => 'Épreuves & Corrigés — CFEE, BFEM, BAC, BTS Sénégal PDF',
            'metaDescription' => 'Corrigé BFEM Sénégal, sujets BAC, BTS, CFEE et CAP à télécharger gratuitement en PDF. Épreuves officielles et corrigés classés par matière, série et année.',
            'heading' => 'Épreuves & Corrigés du Sénégal',
        ]);

        return $view->with('epreuveDocuments', $this->getEpreuveCategoryDocuments());
    }

    /**
     * Documents (catégorie "épreuves") mis en avant dans le carousel affiché
     * sur le hub /epreuves et sur les fiches épreuve individuelles.
     */
    private function getEpreuveCategoryDocuments()
    {
        return Cache::remember('epreuve_category_documents', 900, function () {
            return \App\Models\Document::published()
                ->active()
                ->whereHas('category', fn($q) => $q->where('slug', 'epreuves'))
                ->with(['category:id,name,slug'])
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'price', 'discount_price', 'download_count', 'sales_count', 'views_count', 'is_featured')
                ->withAvg('approvedReviews as average_rating', 'rating')
                ->withCount('approvedReviews as reviews_count')
                ->orderByDesc('is_featured')
                ->orderByDesc('download_count')
                ->orderByDesc('views_count')
                ->take(12)
                ->get();
        });
    }

    /**
     * Page SEO par examen : /epreuves/examen/bac[/mathematiques]
     */
    public function exam(Request $request, string $exam, ?string $matiereSlug = null)
    {
        abort_unless(isset(Epreuve::EXAMS[$exam]), 404);
        $examLabel = Epreuve::EXAMS[$exam];

        $matiere = $matiereSlug ? EpreuveMatiere::where('slug', $matiereSlug)->firstOrFail() : null;

        // Forcer l'examen ; ne forcer la matière que si elle vient de l'URL,
        // sinon on écraserait le filtre matière choisi dans le formulaire.
        $request->merge(['exam' => $exam]);
        if ($matiere) {
            $request->merge(['matiere' => $matiere->slug]);
        }

        $titleSuffix = $matiere ? " de {$matiere->name}" : '';

        return $this->renderListing($request, [
            'pageTitle' => "Épreuves{$titleSuffix} {$examLabel} Sénégal PDF - Sujets et corrigés",
            'metaDescription' => "Épreuves{$titleSuffix} du {$examLabel} au Sénégal en PDF : sujets officiels, examens blancs et corrigés à télécharger gratuitement, classés par année et par série.",
            'heading' => "Épreuves {$examLabel}" . ($matiere ? " — {$matiere->name}" : ''),
            'fixedExam' => $exam,
            'fixedMatiere' => $matiere,
        ]);
    }

    /**
     * Page SEO par classe : /epreuves/classe/3eme[/mathematiques]
     */
    public function level(Request $request, string $level, ?string $matiereSlug = null)
    {
        $levels = Epreuve::flatLevels();
        abort_unless(isset($levels[$level]), 404);
        $levelLabel = $levels[$level];

        $matiere = $matiereSlug ? EpreuveMatiere::where('slug', $matiereSlug)->firstOrFail() : null;

        // Forcer la classe ; ne forcer la matière que si elle vient de l'URL.
        $request->merge(['level' => $level]);
        if ($matiere) {
            $request->merge(['matiere' => $matiere->slug]);
        }

        $titleSuffix = $matiere ? " de {$matiere->name}" : '';

        return $this->renderListing($request, [
            'pageTitle' => "Épreuves et devoirs{$titleSuffix} {$levelLabel} Sénégal PDF",
            'metaDescription' => "Devoirs, compositions et épreuves{$titleSuffix} pour la classe de {$levelLabel} au Sénégal : documents PDF gratuits avec corrigés.",
            'heading' => "Classe de {$levelLabel}" . ($matiere ? " — {$matiere->name}" : ''),
            'fixedLevel' => $level,
            'fixedMatiere' => $matiere,
        ]);
    }

    /**
     * Fiche document : visionneuse PDF + téléchargement.
     */
    public function show(string $slug)
    {
        $epreuve = Cache::remember("epreuve_show_{$slug}", 1800, function () use ($slug) {
            return Epreuve::published()->with('matiere')->where('slug', $slug)->firstOrFail();
        });

        // Incrémenter les vues de façon probabiliste : 1 chance sur 10, on ajoute 10
        // Évite une écriture DB synchrone à chaque visite
        if (rand(1, 10) === 1) {
            Epreuve::where('id', $epreuve->id)->increment('views_count', 10);
        }

        $related = Cache::remember("epreuve_related_{$epreuve->id}", 3600, function () use ($epreuve) {
            return Epreuve::published()
                ->where('id', '!=', $epreuve->id)
                ->where(function ($q) use ($epreuve) {
                    if ($epreuve->matiere_id) {
                        $q->where('matiere_id', $epreuve->matiere_id);
                    }
                    if ($epreuve->exam) {
                        $q->orWhere('exam', $epreuve->exam);
                    } elseif ($epreuve->level) {
                        $q->orWhere('level', $epreuve->level);
                    }
                })
                ->orderByDesc('year')
                ->limit(6)
                ->get();
        });

        // AdSense cachées ici pour éviter 2 requêtes DB dans la vue
        $adsSettings = Cache::remember('adsense_settings', 3600, fn() => \App\Models\AdSenseSetting::first());
        $adsUnit     = Cache::remember('adsense_unit_content', 3600, fn() => \App\Models\AdSenseUnit::getUnitsForPosition('content')->first());

        $epreuveDocuments = $this->getEpreuveCategoryDocuments();

        // Avis approuvés
        $reviews = $epreuve->approvedReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('epreuves.show', compact('epreuve', 'related', 'adsSettings', 'adsUnit', 'epreuveDocuments', 'reviews'));
    }

    /**
     * Téléchargement libre du PDF de l'épreuve (gratuit) avec compteur.
     * Le corrigé n'est JAMAIS servi ici : il est payant et passe par
     * CorrigeController::download avec un token d'achat valide.
     */
    public function download(int $id)
    {
        $epreuve = Epreuve::published()->findOrFail($id);

        // Un document qui est lui-même un corrigé n'est pas téléchargeable librement.
        abort_if($epreuve->isCorrige(), 403);

        // Une épreuve payante nécessite un achat préalable.
        if (!$epreuve->isFree()) {
            return redirect()->route('epreuves.show', $epreuve->slug)
                ->with('paywall', 'Cette épreuve est payante. Achetez-la pour la télécharger.');
        }

        $path = $epreuve->file_path;
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        Epreuve::where('id', $epreuve->id)->increment('downloads_count');

        return Storage::disk('public')->download($path, $epreuve->slug . '.pdf');
    }

    /**
     * Sert le PDF de l'épreuve en ligne (inline) pour la visionneuse intégrée.
     * Refuse tout corrigé : un corrigé ne doit jamais être affiché sans achat.
     */
    public function view(int $id)
    {
        $epreuve = Epreuve::published()->findOrFail($id);

        // Jamais d'aperçu pour un corrigé ou une épreuve payante.
        abort_if($epreuve->isCorrige(), 403);
        abort_if(!$epreuve->isFree(), 403);

        $path = $epreuve->file_path;
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $epreuve->slug . '.pdf"',
        ]);
    }

    /**
     * Rendu commun du listing avec filtres.
     */
    private function renderListing(Request $request, array $seo)
    {
        $query = Epreuve::published()->with('matiere');

        $exam = trim((string) $request->get('exam', ''));
        $level = trim((string) $request->get('level', ''));
        $matiereSlug = trim((string) $request->get('matiere', ''));
        $serie = trim((string) $request->get('serie', ''));
        $year = (int) $request->get('year', 0);
        $type = trim((string) $request->get('type', ''));
        $search = trim((string) $request->get('q', ''));

        if ($exam !== '' && isset(Epreuve::EXAMS[$exam])) {
            $query->where('exam', $exam);
        }
        if ($level !== '' && isset(Epreuve::flatLevels()[$level])) {
            $query->where('level', $level);
        }
        if ($matiereSlug !== '') {
            $matiereId = Cache::remember("matiere_id_{$matiereSlug}", 3600,
                fn() => EpreuveMatiere::where('slug', $matiereSlug)->value('id')
            );
            if ($matiereId) {
                $query->where('matiere_id', $matiereId);
            }
        }
        if ($serie !== '') {
            $query->where('serie', $serie);
        }
        if ($year > 0) {
            // Inclure les annales dont la plage couvre l'année filtrée
            $query->where('year', '<=', $year)
                  ->where(function ($q) use ($year) {
                      $q->whereNull('year_end')->orWhere('year_end', '>=', $year);
                  });
        }
        if ($type !== '' && isset(Epreuve::TYPES[$type])) {
            $query->where('type', $type);
        }
        if ($search !== '') {
            $query->where('title', 'like', "%{$search}%");
        }

        $epreuves = $query
            ->orderByDesc('is_featured')
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->paginate(30)
            ->appends($request->only('exam', 'level', 'matiere', 'serie', 'year', 'type', 'q'));

        $matieres = Cache::remember('epreuve_matieres', 3600,
            fn() => EpreuveMatiere::orderBy('order')->orderBy('name')->get()
        );

        $years = Cache::remember('epreuve_years', 3600, function () {
            $startYears = Epreuve::published()->whereNotNull('year')->pluck('year');
            $endYears   = Epreuve::published()->whereNotNull('year_end')->pluck('year_end');
            return $startYears->merge($endYears)->unique()->sort()->reverse()->values();
        });

        $stats = Cache::remember('epreuve_stats', 1800, fn() => [
            'total'     => Epreuve::published()->count(),
            'downloads' => (int) Epreuve::published()->sum('downloads_count'),
        ]);

        return view('epreuves.index', array_merge($seo, [
            'epreuves' => $epreuves,
            'matieres' => $matieres,
            'years' => $years,
            'stats' => $stats,
            'filters' => compact('exam', 'level', 'matiereSlug', 'serie', 'year', 'type', 'search'),
        ]))->with([
            'fixedExam' => $seo['fixedExam'] ?? null,
            'fixedLevel' => $seo['fixedLevel'] ?? null,
            'fixedMatiere' => $seo['fixedMatiere'] ?? null,
        ]);
    }
}
