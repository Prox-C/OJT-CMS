<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hte_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hte_id')->constrained('htes')->onDelete('cascade');
            
            // Correct foreign key reference for skill_id
            $table->unsignedBigInteger('skill_id');
            $table->foreign('skill_id')
                  ->references('skill_id')
                  ->on('skills')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            $table->unique(['hte_id', 'skill_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hte_skill');
    }
};