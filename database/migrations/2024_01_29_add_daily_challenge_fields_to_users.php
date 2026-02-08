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
            $table->integer('daily_challenge_streak')->default(0)->after('remember_token');
            $table->date('last_daily_challenge_date')->nullable()->after('daily_challenge_streak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_challenge_streak', 'last_daily_challenge_date']);
        });
    }
};
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('daily_challenge_streak')->default(0)->after('remember_token');
            $table->date('last_daily_challenge_date')->nullable()->after('daily_challenge_streak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_challenge_streak', 'last_daily_challenge_date']);
        });
    }
};
