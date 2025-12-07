<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_favori_peut_etre_cree()
    {
        $user = User::factory()->create();

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
            'favoritable_name' => 'HTML5',
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_avoir_plusieurs_favoris()
    {
        $user = User::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
            'favoritable_name' => 'HTML5',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'css3',
            'favoritable_name' => 'CSS3',
        ]);

        $this->assertCount(2, $user->favorites);
    }
}

