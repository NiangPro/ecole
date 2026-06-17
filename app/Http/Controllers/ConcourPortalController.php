<?php

namespace App\Http\Controllers;

use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ConcourPortalController extends Controller
{
    public function index()
    {
        $institutions = Epreuve::LEVELS['concours'];

        // Compte docs + dernière année par institution
        $counts = Epreuve::published()
            ->where('exam', 'concours')
            ->selectRaw('level, count(*) as total, max(year) as last_year')
            ->groupBy('level')
            ->get()
            ->keyBy('level');

        $cards = collect($institutions)->map(function ($label, $slug) use ($counts) {
            $row = $counts->get($slug);
            return [
                'slug'      => $slug,
                'label'     => $label,
                'total'     => $row?->total ?? 0,
                'last_year' => $row?->last_year,
            ];
        })->sortByDesc('total')->values();

        $stats = [
            'total'        => $counts->sum('total'),
            'institutions' => $counts->count(),
        ];

        return view('epreuves.concours', compact('cards', 'stats'));
    }

    public function show(string $institution)
    {
        $institutions = Epreuve::LEVELS['concours'];
        abort_unless(isset($institutions[$institution]), 404);

        $institutionLabel = $institutions[$institution];

        $epreuves = Epreuve::published()
            ->where('exam', 'concours')
            ->where('level', $institution)
            ->whereNotIn('type', ['corrige'])
            ->with('matiere')
            ->orderByDesc('year')
            ->get();

        $matiereIds = $epreuves->pluck('matiere_id')->unique()->filter()->values();
        $matieres   = EpreuveMatiere::whereIn('id', $matiereIds)
            ->orderBy('order')->orderBy('name')->get();

        $years = $epreuves->pluck('year')->filter()->unique()->sortDesc()->values();

        $grid = [];
        foreach ($epreuves as $e) {
            if (! $e->year) continue;
            $grid[$e->year][$e->matiere_id ?? 0][] = $e;
        }

        $stats = [
            'total'     => $epreuves->count(),
            'years'     => $years->count(),
            'downloads' => (int) $epreuves->sum('downloads_count'),
        ];

        // Réutilise la vue portail générique
        return view('epreuves.portail', [
            'exam'       => 'concours',
            'examLabel'  => $institutionLabel,
            'matieres'   => $matieres,
            'years'      => $years,
            'grid'       => $grid,
            'stats'      => $stats,
            'isConcours' => true,
            'institution'=> $institution,
            'allInstitutions' => $institutions,
        ]);
    }

    public function pack(string $institution, int $year)
    {
        $institutions = Epreuve::LEVELS['concours'];
        abort_unless(isset($institutions[$institution]), 404);
        abort_unless($year >= 1990 && $year <= 2030, 404);

        $epreuves = Epreuve::published()
            ->where('exam', 'concours')
            ->where('level', $institution)
            ->where('year', $year)
            ->whereNotIn('type', ['corrige'])
            ->where(fn ($q) => $q->whereNull('price')->orWhere('price', '<=', 0))
            ->whereNotNull('file_path')
            ->where('file_path', '!=', '')
            ->get()
            ->filter(fn ($e) => Storage::disk('public')->exists($e->file_path));

        abort_if($epreuves->isEmpty(), 404);

        $tmpFile = tempnam(sys_get_temp_dir(), 'pack_concours_');
        $zip     = new ZipArchive();
        $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($epreuves as $e) {
            $zip->addFile(Storage::disk('public')->path($e->file_path), $e->slug . '.pdf');
            Epreuve::where('id', $e->id)->increment('downloads_count');
        }

        $zip->close();

        $name = strtolower($institutions[$institution]);
        return response()
            ->download($tmpFile, "concours-{$name}-senegal-{$year}.zip", ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }
}
