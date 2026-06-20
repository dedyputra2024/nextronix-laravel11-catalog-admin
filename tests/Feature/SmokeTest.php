<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_and_shop_can_be_opened(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/')->assertOk();
        $this->get('/shop')->assertOk();
    }

    public function test_staff_can_access_admin_dashboard(): void
    {
        $this->seed(DatabaseSeeder::class);

        $staff = User::where('email', 'staff@electro.test')->firstOrFail();

        $this->actingAs($staff)
            ->get('/admin')
            ->assertOk();
    }
}
