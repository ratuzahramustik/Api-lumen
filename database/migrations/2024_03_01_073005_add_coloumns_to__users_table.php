<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('_users', function (Blueprint $table) {
            $table->string('api_key')->nullable()->uniqie();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('_users', function (Blueprint $table) {
            $table->dropColoumn('api_key');
        });
    }
};
