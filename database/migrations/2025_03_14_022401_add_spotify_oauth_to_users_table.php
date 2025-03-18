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
        Schema::table('users', function (Blueprint $table) {
            $table->string('spotify_access_token', 1000)->nullable();
            $table->string('spotify_refresh_token', 1000)->nullable();
            $table->timestamp('spotify_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $$table->dropColumn('spotify_access_token');
            $table->dropColumn('spotify_refresh_token');
            $table->dropColumn('spotify_expires_at');
        });
    }
};
