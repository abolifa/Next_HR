<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Document;
use App\Models\Employee;
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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@gmail.com',
        ]);

        Company::factory()
            ->count(10)
            ->create()
            ->each(function ($company) {
                // Employees
                $company->employees()->createMany(
                    Employee::factory()
                        ->count(10)
                        ->make(['company_id' => $company->id])
                        ->each(fn($e) => $e->makeVisible('password'))
                        ->toArray()
                );

                // Documents
                $company->documents()->createMany(
                    Document::factory()
                        ->count(10)
                        ->make(['company_id' => $company->id])
                        ->toArray()
                );

                // Bank Accounts
                $company->bankAccounts()->createMany(
                    BankAccount::factory()
                        ->count(5)
                        ->make(['company_id' => $company->id])
                        ->toArray()
                );
            });
    }
}
