<?php

namespace Tests\Feature;

use App\Models\CorrigePurchase;
use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use App\Models\Payment;
use App\Mail\CorrigePurchaseConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CorrigePurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function makeEpreuveWithCorrige(): Epreuve
    {
        Storage::fake('public');
        Storage::disk('public')->put('epreuves/sujet.pdf', '%PDF-1.4 test');
        Storage::disk('public')->put('epreuves/corrige.pdf', '%PDF-1.4 corrige');

        $matiere = EpreuveMatiere::create(['name' => 'Mathématiques', 'slug' => 'mathematiques']);

        return Epreuve::create([
            'title' => 'Épreuve Maths BAC 2024',
            'slug' => 'epreuve-maths-bac-2024',
            'type' => 'epreuve',
            'exam' => 'bac',
            'matiere_id' => $matiere->id,
            'file_path' => 'epreuves/sujet.pdf',
            'corrige_file_path' => 'epreuves/corrige.pdf',
            'file_size' => 1000,
            'status' => 'published',
        ]);
    }

    public function test_checkout_requires_email_or_phone(): void
    {
        $epreuve = $this->makeEpreuveWithCorrige();

        // Ni e-mail ni téléphone → erreur de validation
        $this->post(route('epreuves.corrige.checkout', $epreuve->id), [
            'customer_name' => 'Awa',
        ])->assertSessionHasErrors();

        $this->assertDatabaseCount('corrige_purchases', 0);
    }

    public function test_checkout_with_email_creates_pending_purchase_and_payment(): void
    {
        $epreuve = $this->makeEpreuveWithCorrige();

        $this->post(route('epreuves.corrige.checkout', $epreuve->id), [
            'customer_email' => 'eleve@test.sn',
            'customer_name' => 'Awa',
        ])->assertRedirect();

        $purchase = CorrigePurchase::first();
        $this->assertNotNull($purchase);
        $this->assertSame('pending', $purchase->status);
        $this->assertSame('eleve@test.sn', $purchase->customer_email);
        $this->assertNotNull($purchase->payment_id);
        $this->assertSame('wave', $purchase->payment->payment_method);
    }

    public function test_corrige_pdf_is_not_downloadable_without_valid_token(): void
    {
        $epreuve = $this->makeEpreuveWithCorrige();

        // Token bidon → refusé (404 : aucun achat avec ce token)
        $this->get(route('epreuves.corrige.download', ['token' => 'faux-token']))
            ->assertNotFound();

        // Achat en attente (non payé) → le token ne donne pas accès
        $purchase = CorrigePurchase::create([
            'epreuve_id' => $epreuve->id,
            'customer_email' => 'eleve@test.sn',
            'amount_paid' => 500,
            'status' => 'pending',
            'download_token' => str_repeat('a', 64),
            'token_expires_at' => now()->addDays(30),
        ]);

        $this->get(route('epreuves.corrige.download', ['token' => $purchase->download_token]))
            ->assertForbidden();
    }

    public function test_completed_purchase_delivers_and_allows_download(): void
    {
        Mail::fake();
        $epreuve = $this->makeEpreuveWithCorrige();

        $purchase = CorrigePurchase::create([
            'epreuve_id' => $epreuve->id,
            'customer_email' => 'eleve@test.sn',
            'amount_paid' => 500,
            'status' => 'pending',
        ]);

        // Livraison : génère le token + envoie l'e-mail
        $purchase->markCompletedAndDeliver();
        $purchase->refresh();

        $this->assertSame('completed', $purchase->status);
        $this->assertNotNull($purchase->download_token);
        // Le mailable est en file (ShouldQueue) avec relances
        Mail::assertQueued(CorrigePurchaseConfirmation::class);

        // Le token valide permet le téléchargement du corrigé
        $this->get(route('epreuves.corrige.download', ['token' => $purchase->download_token]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertSame(1, $purchase->fresh()->download_count);
    }

    public function test_epreuve_pdf_view_is_blocked_for_corrige_type(): void
    {
        $matiere = EpreuveMatiere::create(['name' => 'Philo', 'slug' => 'philosophie']);
        Storage::fake('public');
        Storage::disk('public')->put('epreuves/c.pdf', '%PDF-1.4');

        $corrige = Epreuve::create([
            'title' => 'Corrigé seul',
            'slug' => 'corrige-seul',
            'type' => 'corrige',
            'exam' => 'bac',
            'matiere_id' => $matiere->id,
            'file_path' => 'epreuves/c.pdf',
            'file_size' => 100,
            'status' => 'published',
        ]);

        // Aperçu inline et téléchargement libre interdits pour un corrigé
        $this->get(route('epreuves.view', ['id' => $corrige->id]))->assertForbidden();
        $this->get(route('epreuves.download', ['id' => $corrige->id]))->assertForbidden();
    }
}
