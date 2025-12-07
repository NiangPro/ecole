<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SecurityAudit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_audit_de_securite_peut_etre_cree()
    {
        $audit = SecurityAudit::create([
            'event_type' => 'csrf_attack',
            'severity' => SecurityAudit::SEVERITY_HIGH,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent',
            'message' => 'Test message',
        ]);

        $this->assertDatabaseHas('security_audits', [
            'event_type' => 'csrf_attack',
            'severity' => 'high',
            'ip_address' => '127.0.0.1',
        ]);
    }

    /** @test */
    public function log_peut_enregistrer_un_evenement()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $audit = SecurityAudit::log(
            'csrf_attack',
            SecurityAudit::SEVERITY_HIGH,
            ['message' => 'Test', 'response_code' => 419]
        );

        $this->assertDatabaseHas('security_audits', [
            'event_type' => 'csrf_attack',
            'severity' => 'high',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function sanitizeRequestData_masque_les_mots_de_passe()
    {
        // Utiliser la réflexion pour accéder à la méthode protégée
        $reflection = new \ReflectionClass(SecurityAudit::class);
        $method = $reflection->getMethod('sanitizeRequestData');
        $method->setAccessible(true);

        $data = [
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $sanitized = $method->invoke(null, $data);

        $this->assertEquals('test@example.com', $sanitized['email']);
        $this->assertEquals('***REDACTED***', $sanitized['password']);
        $this->assertEquals('***REDACTED***', $sanitized['password_confirmation']);
    }

    /** @test */
    public function scopeBySeverity_filtre_par_severite()
    {
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_HIGH]);
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_LOW]);

        $highSeverity = SecurityAudit::bySeverity(SecurityAudit::SEVERITY_HIGH)->get();

        $this->assertCount(1, $highSeverity);
        $this->assertEquals(SecurityAudit::SEVERITY_HIGH, $highSeverity->first()->severity);
    }

    /** @test */
    public function scopeCritical_retourne_les_evenements_critiques_et_eleves()
    {
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_CRITICAL]);
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_HIGH]);
        SecurityAudit::factory()->create(['severity' => SecurityAudit::SEVERITY_MEDIUM]);

        $critical = SecurityAudit::critical()->get();

        $this->assertGreaterThanOrEqual(2, $critical->count());
    }
}

