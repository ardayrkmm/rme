<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class GenderFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
    }

    public function test_can_get_genders()
    {
        DB::table('genders')->insert(['name' => 'Laki-laki']);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/genders');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }
}
