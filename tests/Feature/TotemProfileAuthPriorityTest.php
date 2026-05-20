<?php

namespace Tests\Feature;

use App\Models\Establecimiento;
use App\Models\Totem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TotemProfileAuthPriorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_prefers_bearer_token_user_over_web_session_user(): void
    {
        $establecimiento = Establecimiento::create([
            'codigo' => 'H1',
            'nombre' => 'Hotel 1',
        ]);

        $totem = Totem::create([
            'establecimiento_id' => $establecimiento->id,
            'codigo' => 'T1',
        ]);

        $userSession = User::factory()->create([
            'totem_id' => null,
        ]);
        $userSession->establecimientos()->attach($establecimiento->id);

        $userToken = User::factory()->create([
            'totem_id' => $totem->id,
        ]);

        // Simulate a browser session authenticated as $userSession.
        $this->actingAs($userSession, 'web');

        // But call the API with a Bearer token that belongs to $userToken.
        $token = $userToken->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/totems/profile');

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $userToken->id,
            'email' => $userToken->email,
        ]);
    }
}
