<?php

namespace App\Http\Controllers;

use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EpreuveController extends Controller
{
    /**
     * Hub Épreuves & Corrigés : liste filtrable (examen, niveau, matière, série, année).
     */
    public function index(Request $request)
    {
        return $this->renderListing($request, [
            'pageTitle' => 'Épreuves & Corrigés - Examens et concours du Sénégal',
            'metaDescription' => 'Téléchargez gratuitement les épreuves et corrigés des examens du Sénégal : CFEE, BFEM, BAC, BTS, CAP — toutes matières, toutes séries, du CI à la Terminale.',
            'heading' => 'Épreuves & Corrigés',
        ]);
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
        $epreuve = Epreuve::published()->with('matiere')->where('slug', $slug)->firstOrFail();

        Epreuve::where('id', $epreuve->id)->increment('views_count');

        $related = Epreuve::published()
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

        return view('epreuves.show', compact('epreuve', 'related'));
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

        // Jamais d'aperçu pour un corrigé (document payant).
        abort_if($epreuve->isCorrige(), 403);

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
            $query->whereHas('matiere', fn ($q) => $q->where('slug', $matiereSlug));
        }
        if ($serie !== '') {
            $query->where('serie', $serie);
        }
        if ($year > 0) {
            $query->where('year', $year);
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
            ->paginate(18)
            ->appends($request->only('exam', 'level', 'matiere', 'serie', 'year', 'type', 'q'));

        $matieres = EpreuveMatiere::orderBy('order')->orderBy('name')->get();

        $years = Epreuve::published()->whereNotNull('year')
            ->select('year')->distinct()->orderByDesc('year')->pluck('year');

        $stats = [
            'total' => Epreuve::published()->count(),
            'downloads' => (int) Epreuve::published()->sum('downloads_count'),
        ];

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
