<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateInternsHteTable extends Migration
{
    public function up()
    {
        Schema::create('interns_hte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns')->onDelete('cascade');
            $table->foreignId('hte_id')->constrained('htes')->onDelete('cascade');
            $table->enum('status', ['endorsed', 'deployed'])->default('endorsed');
            $table->timestamp('endorsed_at')->useCurrent();
            $table->timestamp('deployed_at')->nullable();
            $table->timestamps();
            $table->unique(['intern_id', 'hte_id']); // prevent duplicate endorsements
        });
    }
    public function down()
    {
        Schema::dropIfExists('interns_hte');
    }
}