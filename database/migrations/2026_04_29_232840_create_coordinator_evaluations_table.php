<?php
// database/migrations/2026_01_15_000001_create_coordinator_evaluations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coordinator_evaluations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('intern_id')->unsigned();
            $table->bigInteger('coordinator_id')->unsigned();
            $table->bigInteger('hte_id')->unsigned()->nullable(); // Which HTE they were assigned to
            
            // Evaluation Criteria (1-5 scale)
            $table->decimal('communication', 3, 2)->comment('Communication skills (1-5)');
            $table->decimal('responsiveness', 3, 2)->comment('Responsiveness to concerns (1-5)');
            $table->decimal('support', 3, 2)->comment('Support provided during internship (1-5)');
            $table->decimal('guidance', 3, 2)->comment('Guidance and mentorship quality (1-5)');
            $table->decimal('fairness', 3, 2)->comment('Fairness in evaluation and treatment (1-5)');
            $table->decimal('professionalism', 3, 2)->comment('Professionalism and conduct (1-5)');
            $table->decimal('timeliness', 3, 2)->comment('Timeliness in responses and actions (1-5)');
            $table->decimal('clarity', 3, 2)->comment('Clarity of instructions and expectations (1-5)');
            
            $table->decimal('average_rating', 3, 2)->comment('Calculated average rating');
            $table->text('comments')->nullable()->comment('Additional feedback comments');
            $table->text('suggestions')->nullable()->comment('Suggestions for improvement');
            
            $table->enum('status', ['pending', 'submitted'])->default('submitted');
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            
            $table->foreign('intern_id')->references('id')->on('interns')->onDelete('cascade');
            $table->foreign('coordinator_id')->references('id')->on('coordinators')->onDelete('cascade');
            $table->foreign('hte_id')->references('id')->on('htes')->onDelete('set null');
            
            $table->unique(['intern_id', 'coordinator_id']); // One evaluation per intern per coordinator
            $table->index(['coordinator_id', 'status']);
            $table->index('evaluated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('coordinator_evaluations');
    }
};