<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@gmail.com',
        ]);

        Company::factory(10)
            ->has(Document::factory()->count(3))
            ->create();
        BankAccount::factory(10)->create();
    }
}
