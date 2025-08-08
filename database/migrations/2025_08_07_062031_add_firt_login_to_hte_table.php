<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('htes', function (Blueprint $table) {
            $table->boolean('first_login')->default(true)->after('moa_path');
        });
    }

    public function down()
    {
        Schema::table('htes', function (Blueprint $table) {
            $table->dropColumn('first_login');
        });
    }
};