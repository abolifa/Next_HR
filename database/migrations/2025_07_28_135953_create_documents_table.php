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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->enum('paper_type', [
                'commercial_register',   // السجل التجاري
                'business_license',      // الرخصة التجارية
                'economic_operator',     // المشغل الإقتصادي
                'importers_register',    // سجل المستوردين
                'statistical_code',      // الرمز الإحصائي
                'chamber_of_commerce',   // الغرفة التجارية
                'industrial_register',   // السجل الصناعي
                'tax_clearance',         // شهادة سداد ضريبي
                'social_security_clearance', // شهادة سداد ضمان
                'solidarity',            // تضامن
                'articles_of_association',   // النظام الأساسي
                'general_assembly_meeting',  // اجتماع الجمعية العمومية
                'founding_contract',     // عقد التأسيس
                'amendment_contract'     // عقد التعديل
            ]);
            $table->string('document_number')->nullable();
            $table->json('attachments')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
