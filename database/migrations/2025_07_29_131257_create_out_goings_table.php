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
        Schema::create('out_goings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->enum('subject', ['letter', 'invoice', 'contract', 'lc_edit', 'other'])->default('letter');
            $table->string('title');
            $table->string('receiver')->nullable();
            $table->text('body')->nullable();
            $table->json('attachments')->nullable();
            $table->date('date')->nullable();
            $table->string('number')->nullable();
            $table->string('ceo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('out_goings');
    }
};
