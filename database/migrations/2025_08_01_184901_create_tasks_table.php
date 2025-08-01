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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->date('due_date')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('target_mode', ['manual', 'role', 'company', 'company_role'])->default('manual');
            $table->enum('target_role', ['employee', 'accountant', 'driver', 'manager', 'sales', 'hr', 'supervisor'])->nullable(); // only if `target_mode` is `role` or `company_role`
            $table->boolean('for_all_company_employees')->default(false);
            $table->foreignId('created_by_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
