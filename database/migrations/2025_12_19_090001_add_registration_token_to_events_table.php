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
        Schema::table('events', function (Blueprint $table) {
            $table->string('registration_token', 64)->unique()->nullable()->after('status');
            $table->boolean('registration_open')->default(true)->after('registration_token');
        });

        // Generate tokens for existing events
        \App\Models\Event::whereNull('registration_token')->each(function ($event) {
            $event->update(['registration_token' => \Illuminate\Support\Str::random(32)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['registration_token', 'registration_open']);
        });
    }
};
