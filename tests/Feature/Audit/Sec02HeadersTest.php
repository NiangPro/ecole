<?php

namespace Tests\Feature\Audit;

use Tests\TestCase;

/**
 * Audit SEC-02 / CACHE-01 : en-têtes posés par Apache (public/.htaccess).
 * Non testables par une requête Laravel : on vérifie les directives du fichier.
 * Vérification réelle : tests Playwright 03-securite contre le serveur Apache.
 */
class Sec02HeadersTest extends TestCase
{
    private string $htaccess;

    protected function setUp(): void
    {
        parent::setUp();
        $this->htaccess = file_get_contents(public_path('.htaccess'));
    }

    public function test_hsts_un_an_avec_sous_domaines(): void
    {
        $this->assertMatchesRegularExpression('/Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"/', $this->htaccess);
    }

    public function test_permissions_policy_et_csp_report_only(): void
    {
        $this->assertStringContainsString('Header always set Permissions-Policy "camera=(), microphone=(), geolocation=(), payment=()"', $this->htaccess);
        $this->assertMatchesRegularExpression('/Header always set Content-Security-Policy-Report-Only ".*pagead2\.googlesyndication\.com.*"/', $this->htaccess);
        $this->assertDoesNotMatchRegularExpression('/Header (always )?set Content-Security-Policy "/', $this->htaccess, 'CSP bloquante');
    }

    public function test_x_xss_protection_retire(): void
    {
        $this->assertDoesNotMatchRegularExpression('/Header (always )?set X-XSS-Protection/', $this->htaccess);
    }

    public function test_apache_ne_force_plus_de_cache_public_sur_les_pages_php(): void
    {
        $this->assertDoesNotMatchRegularExpression('/FilesMatch "[^"]*php[^"]*">\s*Header set Cache-Control/', $this->htaccess);
        $this->assertMatchesRegularExpression('/<FilesMatch "\\\\\.php\$">\s*ExpiresActive Off/', $this->htaccess);
    }
}
