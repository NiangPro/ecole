<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Créer un utilisateur de test
     */
    protected function createUser(array $attributes = [])
    {
        return \App\Models\User::factory()->create($attributes);
    }

    /**
     * Créer un administrateur de test
     */
    protected function createAdmin(array $attributes = [])
    {
        return \App\Models\User::factory()->create(array_merge([
            'is_admin' => true,
            'is_active' => true,
        ], $attributes));
    }

    /**
     * Se connecter en tant qu'utilisateur
     */
    protected function actingAsUser(array $attributes = [])
    {
        $user = $this->createUser($attributes);
        $this->actingAs($user);
        return $user;
    }

    /**
     * Se connecter en tant qu'administrateur
     */
    protected function actingAsAdmin(array $attributes = [])
    {
        $admin = $this->createAdmin($attributes);
        $this->actingAs($admin);
        return $admin;
    }
}
