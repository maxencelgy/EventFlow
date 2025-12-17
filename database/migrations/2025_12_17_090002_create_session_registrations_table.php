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
        Schema::create('session_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_session_id')->constrained('event_sessions')->onDelete('cascade');
            $table->enum('status', ['inscrit', 'present', 'absent'])->default('inscrit');
            $table->timestamps();

            // Un participant ne peut s'inscrire qu'une fois à une session
            $table->unique(['registration_id', 'event_session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_registrations');
    }
};
