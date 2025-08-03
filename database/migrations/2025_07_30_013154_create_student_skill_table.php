<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_skill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intern_id');
            $table->unsignedBigInteger('skills_id');
            $table->timestamps();

            $table->foreign('intern_id')->references('id')->on('interns')->onDelete('cascade');
            $table->foreign('skills_id')->references('skill_id')->on('skills')->onDelete('cascade');
            
            // Prevent duplicate skill assignments
            $table->unique(['intern_id', 'skills_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_skill');
    }
};