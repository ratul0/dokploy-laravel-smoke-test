<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@dokploy-smoke.test'],
            [
                'name' => 'Dokploy Admin',
                'password' => Hash::make(env('SMOKE_ADMIN_PASSWORD', 'DokployTest123!')),
                'email_verified_at' => now(),
            ],
        );

        User::factory(24)->create();
    }
}
