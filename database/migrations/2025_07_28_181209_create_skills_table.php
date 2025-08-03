<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id('skill_id');
            $table->foreignId('dept_id')->constrained('departments', 'dept_id');
            $table->string('name', 100);
            $table->timestamps();
            
            $table->unique(['dept_id', 'name']); // Ensure skill names are unique per department
        });
    }

    public function down()
    {
        Schema::dropIfExists('skills');
    }
};