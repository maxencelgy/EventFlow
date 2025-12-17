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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['inscrit', 'liste_attente', 'annule', 'present', 'absent'])->default('inscrit');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();
            $table->unsignedInteger('waiting_position')->nullable();
            $table->timestamps();

            // Un participant ne peut s'inscrire qu'une fois à un événement
            $table->unique(['event_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
