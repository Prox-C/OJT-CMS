<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coordinators', function (Blueprint $table) {
            $table->id();
            $table->string('faculty_id')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dept_id')->constrained('departments', 'dept_id')->onDelete('cascade');
            $table->enum('can_add_hte', [0, 1])->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coordinators');
    }
};