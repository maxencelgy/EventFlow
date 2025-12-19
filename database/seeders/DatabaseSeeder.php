<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\EventSession;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer les utilisateurs
        $admin = User::create([
            'name' => 'Admin EventFlow',
            'email' => 'admin@eventflow.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $chefProjet = User::create([
            'name' => 'Marie Dupont',
            'email' => 'chef@eventflow.test',
            'password' => bcrypt('password'),
            'role' => 'chef_projet',
        ]);

        $accueil = User::create([
            'name' => 'Jean Martin',
            'email' => 'accueil@eventflow.test',
            'password' => bcrypt('password'),
            'role' => 'accueil',
        ]);

        // Créer des salles
        $salles = [
            Room::create(['name' => 'Amphithéâtre A', 'capacity' => 200, 'location' => 'Bâtiment Principal']),
            Room::create(['name' => 'Salle Conférence 1', 'capacity' => 50, 'location' => 'Aile Est']),
            Room::create(['name' => 'Salle Conférence 2', 'capacity' => 50, 'location' => 'Aile Est']),
            Room::create(['name' => 'Atelier A', 'capacity' => 25, 'location' => 'Aile Ouest']),
            Room::create(['name' => 'Atelier B', 'capacity' => 25, 'location' => 'Aile Ouest']),
        ];

        // Créer un événement publié (aujourd'hui)
        $eventToday = Event::create([
            'user_id' => $chefProjet->id,
            'title' => 'Conférence Tech Innovation 2024',
            'description' => 'Une journée dédiée aux dernières innovations technologiques. Conférences, ateliers et networking.',
            'date' => now()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'location' => 'Centre de Conférences Paris',
            'max_participants' => 100,
            'status' => 'published',
        ]);

        // Créer un événement à venir
        $eventFuture = Event::create([
            'user_id' => $chefProjet->id,
            'title' => 'Forum Emploi Digital',
            'description' => 'Rencontrez les entreprises qui recrutent dans le secteur du numérique.',
            'date' => now()->addDays(7)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '17:00',
            'location' => 'Espace Grande Arche - La Défense',
            'max_participants' => 150,
            'status' => 'published',
        ]);

        // Créer un événement brouillon
        Event::create([
            'user_id' => $admin->id,
            'title' => 'Hackathon IA 2025',
            'description' => '48h pour créer une solution innovante basée sur l\'IA.',
            'date' => now()->addMonths(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'location' => 'Campus Sciences',
            'max_participants' => 80,
            'status' => 'draft',
        ]);

        // Créer des sessions pour l'événement du jour
        EventSession::create([
            'event_id' => $eventToday->id,
            'room_id' => $salles[0]->id,
            'title' => 'Keynote d\'ouverture',
            'description' => 'Présentation des tendances tech 2024',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'max_participants' => null,
        ]);

        EventSession::create([
            'event_id' => $eventToday->id,
            'room_id' => $salles[3]->id,
            'title' => 'Atelier IA Générative',
            'description' => 'Découvrez les possibilités de l\'IA générative',
            'start_time' => '10:30',
            'end_time' => '12:00',
            'max_participants' => 25,
        ]);

        EventSession::create([
            'event_id' => $eventToday->id,
            'room_id' => $salles[4]->id,
            'title' => 'Workshop Cloud Native',
            'description' => 'Construire des applications cloud-native',
            'start_time' => '10:30',
            'end_time' => '12:00',
            'max_participants' => 25,
        ]);

        // Créer des participants et inscriptions
        $organisations = ['TechCorp', 'StartupIA', 'Digital Agency', 'Banque Centrale', 'Ministère', 'Université Paris', 'Groupe Media', 'HealthTech'];
        $fonctions = ['Développeur', 'Chef de projet', 'CTO', 'Data Scientist', 'Product Manager', 'Designer', 'Consultant', 'Directeur'];

        $participants = [];
        for ($i = 1; $i <= 25; $i++) {
            $participants[] = Participant::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => "participant{$i}@example.com",
                'phone' => fake()->phoneNumber(),
                'organization' => $organisations[array_rand($organisations)],
                'function' => $fonctions[array_rand($fonctions)],
            ]);
        }

        // Inscriptions à l'événement du jour (mix de statuts)
        foreach ($participants as $index => $participant) {
            $status = 'inscrit';
            $checkedInAt = null;
            $waitingPosition = null;

            if ($index < 10) {
                // 10 premiers sont présents
                $status = 'present';
                $checkedInAt = now()->subMinutes(rand(10, 120));
            } elseif ($index < 18) {
                // 8 suivants sont inscrits (en attente de check-in)
                $status = 'inscrit';
            } elseif ($index < 21) {
                // 3 en liste d'attente
                $status = 'liste_attente';
                $waitingPosition = $index - 17;
            } elseif ($index < 23) {
                // 2 annulés
                $status = 'annule';
            } else {
                // 2 absents
                $status = 'absent';
            }

            Registration::create([
                'event_id' => $eventToday->id,
                'participant_id' => $participant->id,
                'status' => $status,
                'registered_at' => now()->subDays(rand(1, 14)),
                'checked_in_at' => $checkedInAt,
                'waiting_position' => $waitingPosition,
            ]);
        }

        // Quelques inscriptions à l'événement futur
        for ($i = 0; $i < 15; $i++) {
            Registration::create([
                'event_id' => $eventFuture->id,
                'participant_id' => $participants[$i]->id,
                'status' => 'inscrit',
                'registered_at' => now()->subDays(rand(1, 7)),
            ]);
        }
    }
}
