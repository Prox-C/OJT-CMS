<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('password_setup_tokens', function (Blueprint $table) {
    $table->string('email')->index();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});

        // Ensure the table has the necessary columns
        Schema::table('password_setup_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('password_setup_tokens', 'email')) {
                $table->string('email')->index();
            }
            if (!Schema::hasColumn('password_setup_tokens', 'token')) {
                $table->string('token');
            }
            if (!Schema::hasColumn('password_setup_tokens', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_setup_tokens');
    }
};
