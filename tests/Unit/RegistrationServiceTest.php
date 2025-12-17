<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RegistrationService $registrationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registrationService = new RegistrationService();
    }

    /**
     * Test d'inscription normale quand des places sont disponibles
     */
    public function test_register_participant_when_spots_available(): void
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

        $participantData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '0612345678',
            'organization' => 'Test Org',
            'function' => 'Developer',
        ];

        $registration = $this->registrationService->register($event, $participantData);

        $this->assertNotNull($registration);
        $this->assertEquals('inscrit', $registration->status);
        $this->assertEquals($event->id, $registration->event_id);
        $this->assertDatabaseHas('participants', ['email' => 'john.doe@example.com']);
    }

    /**
     * Test d'ajout à la liste d'attente quand l'événement est complet
     */
    public function test_register_participant_to_waitlist_when_event_full(): void
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

        // Remplir l'événement
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

        // Tenter une nouvelle inscription
        $participantData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
        ];

        $registration = $this->registrationService->register($event, $participantData);

        $this->assertNotNull($registration);
        $this->assertEquals('liste_attente', $registration->status);
        $this->assertEquals(1, $registration->waiting_position);
    }

    /**
     * Test de promotion automatique depuis la liste d'attente
     */
    public function test_promote_from_waitlist_when_spot_available(): void
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
            'max_participants' => 1,
            'status' => 'published',
        ]);

        // Créer 1 inscription confirmée
        $participant1 = Participant::create([
            'first_name' => 'First',
            'last_name' => 'User',
            'email' => 'first@example.com',
        ]);

        $confirmedRegistration = Registration::create([
            'event_id' => $event->id,
            'participant_id' => $participant1->id,
            'status' => 'inscrit',
            'registered_at' => now(),
        ]);

        // Créer une inscription en liste d'attente
        $participant2 = Participant::create([
            'first_name' => 'Waitlist',
            'last_name' => 'User',
            'email' => 'waitlist@example.com',
        ]);

        $waitlistRegistration = Registration::create([
            'event_id' => $event->id,
            'participant_id' => $participant2->id,
            'status' => 'liste_attente',
            'waiting_position' => 1,
            'registered_at' => now(),
        ]);

        // Vérifier que l'événement est plein
        $this->assertTrue($event->fresh()->isFull());
        
        // Annuler la première inscription (ce qui devrait promouvoir automatiquement)
        $this->registrationService->cancel($confirmedRegistration);

        // Vérifier que l'inscription en attente a été promue
        $waitlistRegistration->refresh();
        $this->assertEquals('inscrit', $waitlistRegistration->status);
    }
}

