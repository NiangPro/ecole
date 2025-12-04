<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Certificate;
use App\Models\FormationProgress;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Afficher les certificats de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        $certificates = $user->certificates()->orderBy('completed_date', 'desc')->get();
        
        return view('dashboard.certificates', compact('user', 'certificates'));
    }

    /**
     * Générer un certificat pour une formation complétée
     */
    public function generate($formationSlug)
    {
        $user = Auth::user();
        
        // Vérifier que la formation est complétée
        $progress = FormationProgress::where('user_id', $user->id)
            ->where('formation_slug', $formationSlug)
            ->where('progress_percentage', 100)
            ->first();

        if (!$progress) {
            return redirect()->route('dashboard.formations')
                ->with('error', 'Vous devez compléter la formation pour obtenir un certificat.');
        }

        // Vérifier si un certificat existe déjà
        $certificate = Certificate::where('user_id', $user->id)
            ->where('formation_slug', $formationSlug)
            ->first();

        if (!$certificate) {
            // Créer un nouveau certificat
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'formation_slug' => $formationSlug,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'completed_date' => $progress->completed_at ?? now(),
                'score' => $progress->progress_percentage,
            ]);
        }

        // Générer le PDF si pas encore généré
        if (!$certificate->pdf_path || !$certificate->hasPdf()) {
            $this->generatePdf($certificate);
        }

        return redirect()->route('certificates.show', $certificate->id);
    }

    /**
     * Afficher un certificat
     */
    public function show($id)
    {
        $user = Auth::user();
        $certificate = Certificate::where('user_id', $user->id)->findOrFail($id);

        return view('dashboard.certificate-show', compact('user', 'certificate'));
    }

    /**
     * Télécharger le PDF d'un certificat
     */
    public function download($id)
    {
        $user = Auth::user();
        $certificate = Certificate::where('user_id', $user->id)->findOrFail($id);

        if (!$certificate->pdf_path || !$certificate->hasPdf()) {
            $this->generatePdf($certificate);
        }

        return response()->download(
            storage_path('app/' . $certificate->pdf_path),
            'certificat-' . $certificate->formation_slug . '.pdf'
        );
    }

    /**
     * Générer le PDF du certificat
     */
    protected function generatePdf(Certificate $certificate)
    {
        $user = $certificate->user;
        $formationName = ucfirst(str_replace('-', ' ', $certificate->formation_slug));

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'user' => $user,
            'formationName' => $formationName,
        ]);

        $pdfPath = 'certificates/' . $certificate->id . '.pdf';
        \Storage::put($pdfPath, $pdf->output());

        $certificate->update([
            'pdf_path' => $pdfPath,
            'generated_at' => now(),
        ]);
    }
}
