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

        foreach (range(1, 24) as $number) {
            $email = sprintf('smoke-user-%02d@dokploy-smoke.test', $number);
            $user = User::factory()->make([
                'name' => sprintf('Smoke User %02d', $number),
                'password' => Hash::make('password'),
            ]);

            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $user->name,
                    'password' => $user->password,
                ],
            );
        }
    }
}
