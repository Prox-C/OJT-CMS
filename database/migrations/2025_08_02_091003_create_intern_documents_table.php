<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intern_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained()->cascadeOnDelete();
            
            $table->enum('type', [
                'requirements_checklist',
                'certificate_of_registration',
                'report_of_grades',
                'application_resume',
                'medical_certificate',
                'parent_consent',
                'insurance_certificate',
                'pre_deployment_certification'
            ]);
            
            $table->string('file_path')->comment('PDF storage path');
            $table->string('original_name')->comment('Original PDF filename');
            $table->timestamps();
            
            // Prevent duplicate document types per intern
            $table->unique(['intern_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_documents');
    }
};