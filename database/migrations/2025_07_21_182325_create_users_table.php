<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fname');
            $table->string('lname');
            $table->enum('sex', ['male', 'female']);
            $table->string('contact');
            $table->string('pic')->nullable()->comment('Profile picture path');
            $table->timestamps();
            
            // Optional: index for better performance on common queries
            $table->index(['email', 'fname', 'lname']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};