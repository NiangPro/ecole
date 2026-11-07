<?php

namespace Tests\Feature\Audit;

use Tests\TestCase;

/**
 * Audit A11Y-01 : garde-fous statiques sur les couleurs corrigées.
 * Le contrôle complet est fait par axe-core (Playwright, specs/06-responsive-a11y) :
 * 0 violation color-contrast sur les 8 pages, en mode clair et sombre (vérifié le 06/11/2026).
 */
class A11y01ContrastTest extends TestCase
{
    public function test_gris_du_pied_de_page_assez_clairs_sur_fond_sombre(): void
    {
        $footer = file_get_contents(resource_path('views/partials/footer.blade.php'));
        preg_match_all('/(?<![-\w])color:\s*oklch\((\d+)% 0 0\)/', $footer, $m);

        // Gris intermédiaires (20–57 %) : illisibles sur le fond sombre. Les valeurs très foncées
        // (texte sombre sur bouton clair) ne sont pas concernées.
        foreach ($m[1] as $lightness) {
            $this->assertFalse((int) $lightness >= 20 && (int) $lightness < 58, "gris de texte à {$lightness} % (contraste < 4,5:1 sur #040a0e)");
        }
    }

    public function test_plus_de_vert_04aa6d_dans_les_formations(): void
    {
        foreach (glob(resource_path('views/formations/*.blade.php')) as $file) {
            $this->assertDoesNotMatchRegularExpression('/#04aa6d\b/i', file_get_contents($file), basename($file));
        }
    }

    public function test_accents_de_texte_fonces_en_mode_clair(): void
    {
        $this->assertStringContainsString('--cd:  #0e7490;', file_get_contents(resource_path('views/emplois/article.blade.php')));
        $this->assertStringContainsString('--lg-accent-text: #0e7490;', file_get_contents(resource_path('css/features/login.css')));
    }

    /** Inscription (mode clair) et lien de la formation HTML5, relevés par axe après les autres correctifs. */
    public function test_inscription_et_lien_dans_le_texte(): void
    {
        $register = file_get_contents(resource_path('views/auth/register.blade.php'));
        $this->assertStringContainsString('body:not(.dark-mode) .field-help { color: rgba(15, 23, 42, .68); }', $register);
        $this->assertStringContainsString('body:not(.dark-mode) .register-brand-eyebrow { color: #155e75; }', $register);

        $html5 = file_get_contents(resource_path('views/formations/html5.blade.php'));
        $this->assertStringContainsString('.np-accent-link { color: #155e75; text-decoration: underline; }', $html5);
    }
}
