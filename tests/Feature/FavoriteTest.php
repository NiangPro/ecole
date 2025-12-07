<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_authentifie_peut_ajouter_un_favori()
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/favorites/toggle', [
            'type' => 'formation',
            'slug' => 'html5',
            'name' => 'HTML5',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
        ]);
    }

    /** @test */
    public function un_utilisateur_authentifie_peut_retirer_un_favori()
    {
        $user = $this->actingAsUser();

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
            'favoritable_name' => 'HTML5',
        ]);

        $response = $this->postJson('/api/favorites/toggle', [
            'type' => 'formation',
            'slug' => 'html5',
            'name' => 'HTML5',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'favoritable_slug' => 'html5',
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_verifier_si_un_favori_existe()
    {
        $user = $this->actingAsUser();

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
            'favoritable_name' => 'HTML5',
        ]);

        $response = $this->getJson('/api/favorites/check?type=formation&slug=html5');

        $response->assertStatus(200)
            ->assertJson(['is_favorite' => true]);
    }

    /** @test */
    public function un_utilisateur_non_authentifie_ne_peut_pas_ajouter_un_favori()
    {
        $response = $this->postJson('/api/favorites/toggle', [
            'type' => 'formation',
            'slug' => 'html5',
            'name' => 'HTML5',
        ]);

        $response->assertStatus(401);
    }
}

