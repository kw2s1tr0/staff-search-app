<?php

namespace Tests\Feature;

use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PositionSearchApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['api:read']);
    }

    public function test_position_index_uses_the_default_order(): void
    {
        $first = Position::factory()->create(['code' => 'Z', 'name' => 'Zulu']);
        $second = Position::factory()->create(['code' => 'A', 'name' => 'Alpha']);

        $this->getJson('/api/positions')
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.id', $first->id)
            ->assertJsonPath('1.id', $second->id);
    }

    public function test_position_index_applies_each_supported_order(): void
    {
        $first = Position::factory()->create(['code' => 'M', 'name' => 'Charlie']);
        $second = Position::factory()->create(['code' => 'Z', 'name' => 'Alpha']);
        $third = Position::factory()->create(['code' => 'A', 'name' => 'Bravo']);

        $cases = [
            ['id', 'desc', [$third->id, $second->id, $first->id]],
            ['code', 'asc', [$third->id, $first->id, $second->id]],
            ['code', 'desc', [$second->id, $first->id, $third->id]],
            ['name', 'asc', [$second->id, $third->id, $first->id]],
            ['name', 'desc', [$first->id, $third->id, $second->id]],
        ];

        foreach ($cases as [$orderBy, $direction, $expectedIds]) {
            $response = $this->getJson('/api/positions?'.http_build_query([
                'order_by' => $orderBy,
                'order_direction' => $direction,
            ]));

            $response
                ->assertOk()
                ->assertJsonPath('0.id', $expectedIds[0])
                ->assertJsonPath('1.id', $expectedIds[1])
                ->assertJsonPath('2.id', $expectedIds[2]);
        }
    }

    public function test_position_index_returns_an_empty_array(): void
    {
        $this->getJson('/api/positions')
            ->assertOk()
            ->assertExactJson([]);
    }

    public function test_position_index_rejects_invalid_order_values(): void
    {
        $this->getJson('/api/positions?order_by=created_at&order_direction=sideways')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['order_by', 'order_direction']);
    }
}
