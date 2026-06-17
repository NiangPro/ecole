<?php

namespace App\Http\Controllers;

use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ExamPortalController extends Controller
{
    private const EXAMS_WITH_PORTAIL = ['cfee', 'bfem', 'bac', 'bts', 'cap'];

    public function show(string $exam)
    {
        abort_unless(in_array($exam, self::EXAMS_WITH_PORTAIL) && isset(Epreuve::EXAMS[$exam]), 404);

        $epreuves = Epreuve::published()
            ->where('exam', $exam)
            ->whereNotIn('type', ['corrige'])
            ->with('matiere')
            ->orderByDesc('year')
            ->get();

        $matiereIds = $epreuves->pluck('matiere_id')->unique()->filter()->values();
        $matieres   = EpreuveMatiere::whereIn('id', $matiereIds)
            ->orderBy('order')->orderBy('name')->get();

        $years = $epreuves->pluck('year')->filter()->unique()->sortDesc()->values();

        // grid[year][matiere_id] = [Epreuve, ...]
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

        $examLabel = Epreuve::EXAMS[$exam];

        return view('epreuves.portail', compact('exam', 'examLabel', 'matieres', 'years', 'grid', 'stats'));
    }

    public function pack(string $exam, int $year)
    {
        abort_unless(isset(Epreuve::EXAMS[$exam]), 404);
        abort_unless($year >= 1990 && $year <= 2030, 404);

        $epreuves = Epreuve::published()
            ->where('exam', $exam)
            ->where('year', $year)
            ->whereNotIn('type', ['corrige'])
            ->where(fn ($q) => $q->whereNull('price')->orWhere('price', '<=', 0))
            ->whereNotNull('file_path')
            ->where('file_path', '!=', '')
            ->get()
            ->filter(fn ($e) => Storage::disk('public')->exists($e->file_path));

        abort_if($epreuves->isEmpty(), 404);

        $tmpFile = tempnam(sys_get_temp_dir(), 'pack_cfee_');
        $zip     = new ZipArchive();
        $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($epreuves as $e) {
            $zip->addFile(Storage::disk('public')->path($e->file_path), $e->slug . '.pdf');
            Epreuve::where('id', $e->id)->increment('downloads_count');
        }

        $zip->close();

        $examLabel = strtolower(Epreuve::EXAMS[$exam]);
        return response()
            ->download($tmpFile, "{$examLabel}-senegal-{$year}.zip", ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }
}
