<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit FCT-02 : « Formations » mène à /formations, « Ressources » ne mène plus à /about.
 */
class Fct02MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_liens_du_menu_desktop(): void
    {
        $html = $this->get('/contact')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#<a href="' . preg_quote(route('formations.all'), '#') . '" class="navbar-link dropdown-toggle#', $html);
        $this->assertDoesNotMatchRegularExpression('#<a href="\#" class="navbar-link dropdown-toggle">#', $html);
        $this->assertDoesNotMatchRegularExpression('#<a href="' . preg_quote(route('about'), '#') . '" class="navbar-link dropdown-toggle#', $html);
        $this->assertMatchesRegularExpression('#<button type="button" aria-haspopup="true" class="navbar-link navbar-link--button dropdown-toggle#', $html);
    }
}
