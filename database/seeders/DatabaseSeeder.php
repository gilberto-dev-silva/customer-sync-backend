<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProfessionSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ]);

        Customer::factory()
            ->count(5)
            ->create();
    }
}
