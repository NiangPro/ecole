<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class HomePageTest extends DuskTestCase
{
    /**
     * Test de la page d'accueil
     */
    public function test_la_page_daccueil_saffiche_correctement(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('NiangProgrammeur')
                    ->assertSee('Formations')
                    ->assertSee('Exercices');
        });
    }

    /**
     * Test de navigation vers les formations
     */
    public function test_un_utilisateur_peut_naviguer_vers_les_formations(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Formations')
                    ->assertPathIs('/formations')
                    ->assertSee('HTML5');
        });
    }

    /**
     * Test de la recherche
     */
    public function test_un_utilisateur_peut_rechercher(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->type('q', 'HTML')
                    ->press('Rechercher')
                    ->assertPathIs('/search')
                    ->assertSee('HTML');
        });
    }
}

