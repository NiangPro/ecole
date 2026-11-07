<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit PERF-01 : versions figées + SRI pour cdnjs, toastr retiré des pages publiques,
 * CSS de navigation / ux / social servis par le build Vite.
 */
class Perf01FrontAssetsTest extends TestCase
{
    use RefreshDatabase;

    private function vues(): array
    {
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views')));
        $files = [];
        foreach ($it as $f) {
            if (str_ends_with($f->getFilename(), '.blade.php')) {
                $files[] = $f->getPathname();
            }
        }

        return $files;
    }

    public function test_versions_figees_et_sri_sur_toutes_les_balises_cdnjs(): void
    {
        foreach ($this->vues() as $file) {
            $html = file_get_contents($file);
            $this->assertStringNotContainsString('6.0.0-beta3', $html, $file);
            $this->assertStringNotContainsString('/latest/', $html, $file);

            preg_match_all('#<(?:link|script)\b[^>]*(?:href|src)="https://cdnjs\.cloudflare\.com[^"]+"[^>]*>#', $html, $m);
            foreach ($m[0] as $tag) {
                if (str_contains($tag, 'as="font"')) {
                    continue;
                }
                $this->assertMatchesRegularExpression('/integrity="sha(256|384|512)-[^"]+"/', $tag, basename($file));
                $this->assertStringContainsString('crossorigin="anonymous"', $tag, basename($file));
            }
        }
    }

    public function test_toastr_n_est_plus_charge_sur_les_pages_publiques(): void
    {
        $this->get('/contact')->assertOk()->assertDontSee('toastr', false);
    }

    public function test_les_messages_flash_passent_par_la_notification_native(): void
    {
        $this->withSession(['error' => 'Paiement refusé'])->get('/contact')->assertOk()
            ->assertSee('window.feedbackManager.showError(messages.error)', false)
            ->assertSee('Paiement refus', false);
    }

    public function test_css_de_navigation_servi_par_le_build_vite(): void
    {
        if (! is_file(public_path('build/manifest.json'))) {
            $this->markTestSkipped('build Vite absent (npm run build)');
        }

        $html = $this->get('/contact')->assertOk()->getContent();
        foreach (['navigation', 'ux-improvements', 'social-features'] as $css) {
            $this->assertMatchesRegularExpression('#/build/css/' . $css . '-[\w-]+\.css#', $html, $css);
        }
        $this->assertStringNotContainsString('/css/navigation.css', $html);
    }
}
