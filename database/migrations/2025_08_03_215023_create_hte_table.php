<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('htes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('organization_name');
            $table->enum('type', ['private', 'government', 'ngo', 'educational', 'other']);
            $table->enum('status', ['active', 'new'])->default('new');
            $table->text('address');
            $table->text('description')->nullable();
            $table->integer('slots')->default(0);
            $table->string('moa_path')->nullable();
            $table->enum('moa_is_signed', ['yes', 'no'])->default('no');
            $table->timestamps();
            
            // Optional: Add index for frequently queried columns
            $table->index('status');
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('htes');
    }
};