<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => (string) config('portfolio.admin.email')],
            [
                'name' => (string) config('portfolio.admin.name'),
                'password' => (string) config('portfolio.admin.password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->call(PortfolioDataSeeder::class);
    }
}
