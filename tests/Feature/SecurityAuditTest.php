<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SecurityAudit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_admin_peut_voir_la_liste_des_audits()
    {
        $admin = $this->actingAsAdmin();
        
        SecurityAudit::factory()->count(5)->create();

        $response = $this->get('/admin/security-audit');

        $response->assertStatus(200)
            ->assertSee('Audit de Sécurité');
    }

    /** @test */
    public function un_utilisateur_non_admin_ne_peut_pas_voir_les_audits()
    {
        $user = $this->actingAsUser();

        $response = $this->get('/admin/security-audit');

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function un_admin_peut_filtrer_les_audits_par_severite()
    {
        $admin = $this->actingAsAdmin();
        
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_CRITICAL]);
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_LOW]);

        $response = $this->get('/admin/security-audit?severity=critical');

        $response->assertStatus(200);
    }

    /** @test */
    public function un_admin_peut_exporter_les_audits_en_csv()
    {
        $admin = $this->actingAsAdmin();
        
        SecurityAudit::factory()->count(3)->create();

        $response = $this->get('/admin/security-audit/export/csv');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}

