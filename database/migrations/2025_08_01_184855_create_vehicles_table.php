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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('model')->nullable();
            $table->string('plate_number')->unique();
            $table->string('chassis_number')->nullable()->unique();
            $table->string('registration_number')->nullable();
            $table->string('color')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->date('technical_inspection_due')->nullable();
            $table->decimal('mileage', 10, 2)->nullable();
            $table->enum('status', ['active', 'under_maintenance', 'out_of_service'])->default('active');
            $table->json('attachments')->nullable();
            $table->foreignId('assigned_to_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
