<?php

namespace Tests\Feature\Audit;

use Tests\TestCase;

/**
 * Audit UX-04 : corps de texte ≥ 14 px et cibles tactiles ≥ 24 px en mobile
 * (mesuré dans Chrome en 375 px ; ici, présence des règles à la source).
 */
class Ux04MobileReadabilityTest extends TestCase
{
    public function test_regles_mobiles_presentes(): void
    {
        $home = file_get_contents(resource_path('css/features/homepage.css'));
        $this->assertMatchesRegularExpression('/\.hp-article-excerpt \{ font-size: 0\.875rem; \}/', $home);

        $footer = file_get_contents(resource_path('views/partials/footer.blade.php'));
        $this->assertStringContainsString('.ft__newsletter-desc { font-size: 0.875rem; }', $footer);
        $this->assertStringContainsString('min-inline-size: 24px;', $footer);
        // La règle mobile doit venir APRÈS la règle de base, sinon elle est écrasée.
        $this->assertGreaterThan(strpos($footer, '.ft__tagline {'), strpos($footer, '.ft__newsletter-desc { font-size: 0.875rem; }'));

        $cookie = file_get_contents(resource_path('views/partials/cookie-banner.blade.php'));
        $this->assertStringNotContainsString('.ck-text p { font-size: .75rem; }', $cookie);

        foreach (glob(resource_path('views/formations/*.blade.php')) as $f) {
            $src = file_get_contents($f);
            if (str_contains($src, '.copy-code-btn {')) {
                $this->assertStringContainsString('min-height: 24px; /* cible tactile', $src, basename($f));
            }
        }
    }

    public function test_accueil_sans_texte_inline_a_13px(): void
    {
        $this->get('/')->assertOk()->assertDontSee('<p style="font-size:0.8125rem', false);
    }
}
