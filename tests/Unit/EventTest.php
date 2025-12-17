<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la capacité maximale est respectée
     */
    public function test_event_is_full_when_max_participants_reached(): void
    {
        $user = User::factory()->create();
        
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'description' => 'Test Description',
            'date' => now()->addDays(7),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'location' => 'Test Location',
            'max_participants' => 2,
            'status' => 'published',
        ]);

        // Créer 2 participants et les inscrire
        for ($i = 0; $i < 2; $i++) {
            $participant = Participant::create([
                'first_name' => "Test$i",
                'last_name' => "User$i",
                'email' => "test$i@example.com",
            ]);

            Registration::create([
                'event_id' => $event->id,
                'participant_id' => $participant->id,
                'status' => 'inscrit',
                'registered_at' => now(),
            ]);
        }

        $event->refresh();
        
        $this->assertTrue($event->isFull());
        $this->assertEquals(0, $event->available_spots);
    }

    /**
     * Test du calcul des places disponibles
     */
    public function test_available_spots_calculation(): void
    {
        $user = User::factory()->create();
        
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'description' => 'Test Description',
            'date' => now()->addDays(7),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'location' => 'Test Location',
            'max_participants' => 10,
            'status' => 'published',
        ]);

        // Au départ, toutes les places sont disponibles
        $this->assertEquals(10, $event->available_spots);

        // Ajouter 3 inscriptions confirmées
        for ($i = 0; $i < 3; $i++) {
            $participant = Participant::create([
                'first_name' => "Test$i",
                'last_name' => "User$i",
                'email' => "test$i@example.com",
            ]);

            Registration::create([
                'event_id' => $event->id,
                'participant_id' => $participant->id,
                'status' => 'inscrit',
                'registered_at' => now(),
            ]);
        }

        $event->refresh();
        
        // Vérifier qu'il reste 7 places
        $this->assertEquals(7, $event->available_spots);
        $this->assertFalse($event->isFull());
    }

    /**
     * Test de la génération automatique du token d'inscription
     */
    public function test_registration_token_is_generated_automatically(): void
    {
        $user = User::factory()->create();
        
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'description' => 'Test Description',
            'date' => now()->addDays(7),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'location' => 'Test Location',
            'max_participants' => 10,
            'status' => 'published',
        ]);

        $this->assertNotNull($event->registration_token);
        $this->assertEquals(32, strlen($event->registration_token));
    }
}
