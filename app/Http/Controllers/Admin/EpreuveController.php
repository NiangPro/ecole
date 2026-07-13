<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EpreuveController extends Controller
{
    /**
     * Liste admin avec filtres.
     */
    public function index(Request $request)
    {
        $query = Epreuve::with('matiere');

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where('title', 'like', "%{$search}%");
        }
        if ($exam = $request->get('exam')) {
            $query->where('exam', $exam);
        }
        if ($level = $request->get('level')) {
            $query->where('level', $level);
        }
        if ($matiereId = $request->get('matiere_id')) {
            $query->where('matiere_id', $matiereId);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        // Filtre : nombre minimum de téléchargements
        if (($min = $request->get('min_downloads')) !== null && $min !== '') {
            $query->where('downloads_count', '>=', (int) $min);
        }
        // Filtre : présence d'un corrigé
        if ($request->get('corrige') === '1') {
            $query->whereNotNull('corrige_file_path')->where('corrige_file_path', '!=', '');
        } elseif ($request->get('corrige') === '0') {
            $query->where(function ($q) {
                $q->whereNull('corrige_file_path')->orWhere('corrige_file_path', '');
            });
        }

        // Tri (par défaut : plus récents)
        $sort = $request->get('sort', 'recent');
        match ($sort) {
            'downloads_desc' => $query->orderByDesc('downloads_count'),
            'downloads_asc'  => $query->orderBy('downloads_count'),
            default          => $query->orderByDesc('created_at'),
        };

        $epreuves = $query
            ->paginate(20)
            ->appends($request->only('q', 'exam', 'level', 'matiere_id', 'status', 'min_downloads', 'sort', 'corrige'));

        $matieres = EpreuveMatiere::orderBy('order')->orderBy('name')->get();

        return view('admin.epreuves.index', compact('epreuves', 'matieres'));
    }

    public function create()
    {
        $matieres = EpreuveMatiere::orderBy('order')->orderBy('name')->get();

        return view('admin.epreuves.create', ['epreuve' => null, 'matieres' => $matieres]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['file_path'] = $request->file('file')->store('epreuves', 'public');
        $data['file_name'] = $request->file('file')->getClientOriginalName();
        $data['file_size'] = $request->file('file')->getSize();

        if ($request->hasFile('corrige_file')) {
            $data['corrige_file_path'] = $request->file('corrige_file')->store('epreuves', 'public');
        }

        // Pré-remplir les champs vides depuis le nom du fichier
        $guess = Epreuve::guessMetadata($data['file_name'] . ' ' . $data['title']);
        foreach (['exam', 'level', 'matiere_id', 'serie', 'year', 'type'] as $field) {
            if (empty($data[$field]) && isset($guess[$field])) {
                $data[$field] = $guess[$field];
            }
        }

        $data['slug'] = $this->uniqueSlug($data['slug'] ?? '', $data['title']);

        $epreuve = Epreuve::create($data);
        Epreuve::clearHomepageCache();

        return redirect()->route('admin.epreuves.edit', $epreuve)
            ->with('success', 'Épreuve créée avec succès.');
    }

    public function edit(Epreuve $epreuve)
    {
        $matieres = EpreuveMatiere::orderBy('order')->orderBy('name')->get();

        return view('admin.epreuves.edit', compact('epreuve', 'matieres'));
    }

    /**
     * Téléchargement du PDF depuis l'admin, sans les restrictions de la route
     * publique (statut publié, gratuité, type non-corrigé) : un admin doit
     * pouvoir consulter un brouillon ou un corrigé pour le classer.
     */
    public function download(Epreuve $epreuve)
    {
        abort_unless(
            $epreuve->file_path && Storage::disk('public')->exists($epreuve->file_path),
            404
        );

        return Storage::disk('public')->download(
            $epreuve->file_path,
            $epreuve->file_name ?? ($epreuve->slug . '.pdf')
        );
    }

    public function update(Request $request, Epreuve $epreuve)
    {
        $data = $this->validateData($request, $epreuve);

        if ($request->hasFile('file')) {
            if ($epreuve->file_path) {
                Storage::disk('public')->delete($epreuve->file_path);
            }
            $data['file_path'] = $request->file('file')->store('epreuves', 'public');
            $data['file_name'] = $request->file('file')->getClientOriginalName();
            $data['file_size'] = $request->file('file')->getSize();
        }

        if ($request->hasFile('corrige_file')) {
            if ($epreuve->corrige_file_path) {
                Storage::disk('public')->delete($epreuve->corrige_file_path);
            }
            $data['corrige_file_path'] = $request->file('corrige_file')->store('epreuves', 'public');
        }

        if (!empty($data['slug']) && $data['slug'] !== $epreuve->slug) {
            $data['slug'] = $this->uniqueSlug($data['slug'], $data['title'], $epreuve->id);
        } else {
            unset($data['slug']);
        }

        $oldSlug = $epreuve->slug;
        $epreuve->update($data);
        Epreuve::clearHomepageCache();
        // Invalider le cache page de cette épreuve
        \App\Http\Middleware\PageCache::forget(route('epreuves.show', $oldSlug));
        if ($epreuve->slug !== $oldSlug) {
            \App\Http\Middleware\PageCache::forget(route('epreuves.show', $epreuve->slug));
        }
        \Illuminate\Support\Facades\Cache::forget("epreuve_show_{$oldSlug}");
        \Illuminate\Support\Facades\Cache::forget("epreuve_related_{$epreuve->id}");

        return redirect()->route('admin.epreuves.edit', $epreuve)
            ->with('success', 'Épreuve mise à jour.');
    }

    public function destroy(Epreuve $epreuve)
    {
        foreach ([$epreuve->file_path, $epreuve->corrige_file_path] as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
        $epreuve->delete();
        Epreuve::clearHomepageCache();

        return redirect()->route('admin.epreuves.index')
            ->with('success', 'Épreuve supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.epreuves.index')
                ->with('error', 'Aucune épreuve sélectionnée.');
        }

        $epreuves = Epreuve::whereIn('id', $ids)->get();

        foreach ($epreuves as $epreuve) {
            foreach ([$epreuve->file_path, $epreuve->corrige_file_path] as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $epreuve->delete();
        }

        Epreuve::clearHomepageCache();

        return redirect()->route('admin.epreuves.index')
            ->with('success', count($epreuves) . ' épreuve(s) supprimée(s).');
    }

    public function togglePublish(Epreuve $epreuve)
    {
        $epreuve->update(['status' => $epreuve->status === 'published' ? 'draft' : 'published']);
        Epreuve::clearHomepageCache();

        return back()->with('success', $epreuve->status === 'published' ? 'Épreuve publiée.' : 'Épreuve dépubliée.');
    }

    /**
     * Validation commune création / édition.
     */
    private function validateData(Request $request, ?Epreuve $epreuve = null): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Epreuve::TYPES)),
            'exam' => 'nullable|in:' . implode(',', array_keys(Epreuve::EXAMS)),
            'level' => 'nullable|in:' . implode(',', array_keys(Epreuve::flatLevels())),
            'matiere_id' => 'nullable|exists:epreuve_matieres,id',
            'matiere_new' => 'nullable|string|max:100',
            'serie' => 'nullable|string|max:30',
            'year' => 'nullable|integer|min:1980|max:' . (now()->year + 1),
            'year_end' => 'nullable|integer|min:1980|max:' . (now()->year + 1),
            'description' => 'nullable|string|max:5000',
            'file' => ($epreuve ? 'nullable' : 'required') . '|file|mimes:pdf|max:25600',
            'corrige_file' => 'nullable|file|mimes:pdf|max:25600',
            'price' => 'nullable|integer|min:0|max:1000000',
            'corrige_price' => 'nullable|integer|min:0|max:1000000',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
        ]);

        // Création de matière à la volée
        if (!empty($data['matiere_new'])) {
            $matiere = EpreuveMatiere::firstOrCreate(
                ['slug' => Str::slug($data['matiere_new'])],
                ['name' => $data['matiere_new'], 'order' => 99]
            );
            $data['matiere_id'] = $matiere->id;
        }
        unset($data['matiere_new'], $data['file'], $data['corrige_file']);

        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    private function uniqueSlug(string $requested, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($requested !== '' ? $requested : $title);
        $slug = $base;
        $i = 2;

        while (Epreuve::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
