<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('letter_of_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->string('lc_number')->unique();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->enum('currency', ['USD', 'EUR', 'GBP'])->default('USD');
            $table->enum('status', ['draft', 'issued', 'completed', 'cancelled'])->default('draft');
            $table->text('description')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_of_credits');
    }
};
