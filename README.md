# EventFlow v2

Application de gestion des inscriptions et des présences pour événements professionnels.

## Description

EventFlow v2 permet de :
- 📅 **Gérer des événements** : création, modification, suivi des inscriptions
- 👥 **Gérer les participants** : base de données centralisée, historique de participation
- 📝 **Gérer les inscriptions** : avec liste d'attente automatique et limitation de capacité
- ✅ **Pointer les présences** : interface de check-in le jour J
- 📊 **Suivre les statistiques** : taux de présence, répartition par organisation

## Prérequis

- **Docker Desktop** (pour Laravel Sail)
- **Git**

## Installation

1. **Cloner le dépôt** :
```bash
git clone <url-du-depot>
cd EventFlow
```

2. **Copier le fichier d'environnement** :
```bash
cp .env.example .env
```

3. **Installer les dépendances PHP avec Docker** :
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

4. **Générer la clé d'application** :
```bash
./vendor/bin/sail artisan key:generate
```

## Configuration

Le fichier `.env` contient les variables d'environnement. Les valeurs par défaut fonctionnent avec Docker Sail :

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

## Lancement

1. **Démarrer les conteneurs Docker** :
```bash
./vendor/bin/sail up -d
```

2. **Exécuter les migrations et seeders** :
```bash
./vendor/bin/sail artisan migrate --seed
```

3. **Accéder à l'application** :
- Application : http://localhost
- Mailpit (mails) : http://localhost:8025

4. **Arrêter les conteneurs** :
```bash
./vendor/bin/sail down
```

## Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@eventflow.test | password |
| Chef de projet | chef@eventflow.test | password |
| Accueil | accueil@eventflow.test | password |

## Tests

Lancer les tests automatisés :
```bash
./vendor/bin/sail artisan test
```

## Structure du projet

```
app/
├── Http/Controllers/     # Contrôleurs (Event, Registration, CheckIn, etc.)
├── Models/               # Modèles Eloquent
├── Services/             # Services métier (RegistrationService)
database/
├── migrations/           # Migrations de base de données
├── seeders/              # Données de test
resources/views/
├── layouts/              # Layout principal
├── events/               # Vues événements
├── registrations/        # Vues inscriptions
├── checkin/              # Interface de pointage
├── participants/         # Vues participants
├── auth/                 # Authentification
```

## Fonctionnalités principales

### Gestion des inscriptions
- Inscription avec validation de capacité
- Liste d'attente automatique si complet
- Promotion automatique lors d'une annulation

### Pointage jour J
- Interface de check-in avec recherche
- Statistiques temps réel
- Marquage présent/absent

### Statistiques
- Taux de remplissage
- Taux de présence
- Répartition par organisation

## Technologies

- **Backend** : Laravel 12
- **Base de données** : MySQL 8.0
- **Conteneurisation** : Docker (Laravel Sail)
- **Cache** : Redis
- **Frontend** : Blade + CSS custom
