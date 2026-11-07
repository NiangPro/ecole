<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit A11Y-04 : menu mobile utilisable au clavier (comportement vérifié dans Chrome ;
 * ici, présence des éléments qui le portent).
 */
class A11y04MobileMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_mobile_gere_le_focus(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('aria-expanded="false" aria-controls="mobileMenu"', false)
            ->assertSee("mobileToggle.setAttribute('aria-label', 'Fermer le menu')", false)
            ->assertSee("mobileMenu.setAttribute('inert', '')", false)
            ->assertSee('first.focus({ preventScroll: true })', false)
            ->assertSee('mobileToggle.focus({ preventScroll: true })', false)
            ->assertSee("if (e.key !== 'Tab' || !mobileMenu.classList.contains('active')) return;", false);
    }
}
