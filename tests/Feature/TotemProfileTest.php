<?php

namespace Tests\Feature;

use App\Models\Establecimiento;
use App\Models\Totem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TotemProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_totem_profile_resolves_totem_from_user_establecimientos_when_totem_id_is_null(): void
    {
        $establecimiento = Establecimiento::create([
            'codigo' => 'H1',
            'nombre' => 'Hotel 1',
        ]);

        $totem = Totem::create([
            'establecimiento_id' => $establecimiento->id,
            'codigo' => 'T1',
        ]);

        $user = User::factory()->create([
            'totem_id' => null,
        ]);

        $user->establecimientos()->attach($establecimiento->id);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/v1/totems/profile');

        $response->assertOk();
        $response->assertJsonFragment([
            'totem_id' => $totem->id,
        ]);
        $response->assertJsonFragment([
            'id' => $totem->id,
        ]);
    }
}
