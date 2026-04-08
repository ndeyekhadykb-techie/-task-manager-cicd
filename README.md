# Task Manager - Application Laravel avec CI/CD

<div align="center">

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)]()
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)](#)

**Application professionnelle de gestion de tâches avec pipeline CI/CD complet**

[Installation](#installation) • [Démarrage](#démarrage-rapide) • [Architecture](#architecture) • [Tests](#tests) • [CI/CD](#pipeline-cicd) • [Contribution](#contribution)

</div>

---

## 📋 À propos

**Task Manager** est une application Laravel moderne dédiée à la gestion de tâches. Elle démontre les bonnes pratiques DevOps en mettant en place :

- ✅ Une **architecture MVC** propre et scalable (Laravel 11)
- ✅ Un **pipeline CI/CD complet** avec GitHub Actions
- ✅ Des **tests unitaires et fonctionnels** (PHPUnit)
- ✅ Une **analyse de code statique** (PHPStan)
- ✅ Des **contrôles de qualité** automatisés à chaque commit
- ✅ Un **workflow collaboratif** avec Conventional Commits

### Fonctionnalités

L'application permet aux utilisateurs de :

- 🎯 **Créer** des tâches (titre, description, statut, priorité, échéance)
- ✏️ **Modifier** les tâches existantes
- 🗑️ **Supprimer** des tâches
- 📊 **Lister** toutes les tâches avec filtres par statut
- 👁️ **Visualiser** les détails d'une tâche

---

## 🚀 Démarrage rapide

### Prérequis

- **PHP** 8.2 ou supérieur
- **Composer** (gestionnaire de paquets PHP)
- **Node.js** 18+ et **npm** (pour les assets frontend)
- **MySQL** ou **SQLite** pour la base de données
- **Git** pour le contrôle de version

### Installation

1. **Clonez le repository**
   ```bash
   git clone https://github.com/votre-username/task-manager-cicd.git
   cd task-manager-cicd
   ```

2. **Installez les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installez les dépendances Node.js**
   ```bash
   npm install
   ```

4. **Configurez l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurez la base de données** (dans le fichier `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Exécutez les migrations**
   ```bash
   php artisan migrate
   ```

7. **Compilez les assets frontend**
   ```bash
   npm run dev
   ```

### Lancer l'application

```bash
php artisan serve
```

Accédez à : **http://localhost:8000**

---

## 📁 Architecture

### Structure du projet

```
task-manager-cicd/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── TaskController.php    # Logique métier des tâches
│   │       └── Controller.php        # Classe de base
│   └── Models/
│       ├── Task.php                  # Modèle Eloquent pour les tâches
│       └── User.php                  # Modèle Eloquent pour les utilisateurs
│
├── database/
│   ├── migrations/                   # Scripts de création de BD
│   ├── factories/                    # Générateurs de données de test
│   └── seeders/                      # Population initial de la BD
│
├── resources/
│   ├── css/                          # Feuilles de style
│   ├── js/                           # Scripts JavaScript
│   └── views/
│       └── tasks/                    # Templates Blade pour les tâches
│
├── routes/
│   └── web.php                       # Définition des routes HTTP
│
├── tests/
│   ├── Feature/                      # Tests fonctionnels (intégration)
│   └── Unit/                         # Tests unitaires
│
├── .github/workflows/                # Configuration CI/CD (GitHub Actions)
├── phpunit.xml                       # Configuration PHPUnit
├── phpstan.neon                      # Configuration PHPStan
└── vite.config.js                    # Configuration Vite (build frontend)
```

### Base de données

#### Table `tasks`
| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT PRIMARY KEY | Identifiant unique |
| `title` | VARCHAR(255) | Titre de la tâche |
| `description` | TEXT | Description complète |
| `status` | ENUM | 'todo', 'in_progress', 'done' |
| `priority` | ENUM | 'low', 'medium', 'high' |
| `due_date` | DATE | Date limite |
| `user_id` | INT FK | Propriétaire de la tâche |
| `created_at` | TIMESTAMP | Date création |
| `updated_at` | TIMESTAMP | Date modification |

---

## 🧪 Tests

### Exécuter tous les tests

```bash
php artisan test
```

### Exécuter les tests unitaires uniquement

```bash
php artisan test --filter Unit
```

### Exécuter les tests fonctionnels uniquement

```bash
php artisan test --filter Feature
```

### Générer un rapport de couverture

```bash
php artisan test --coverage
```

### Fichiers de test

- **Tests unitaires** : `tests/Unit/` - Testent les fonctions isolées
- **Tests fonctionnels** : `tests/Feature/` - Testent les workflows complets

---

## 🔍 Qualité du code

### PHPStan (Analyse statique)

Détecte les erreurs de type et les problèmes courants :

```bash
./vendor/bin/phpstan analyse
```

**Configuration** : `phpstan.neon`

---

## 🔄 Pipeline CI/CD

### Workflow GitHub Actions

Le pipeline s'exécute **automatiquement** à chaque :
- 📤 **Push** sur une branche
- 🔀 **Pull Request** vers `main`

### Étapes du workflow

```yaml
1. Checkout du code
2. Setup PHP 8.2
3. Installation des dépendances (Composer)
4. Cache des dépendances
5. Exécution des tests (PHPUnit)
6. Analyse du code (PHPStan)
7. Rapport de build
```

### Fichiers de configuration

- **Workflow principal** : `.github/workflows/ci.yml`
- **Configuration PHPUnit** : `phpunit.xml`
- **Configuration PHPStan** : `phpstan.neon`

### Badges

Ajoutez ce badge dans votre profil GitHub :
```markdown
[![Build Status](https://github.com/votre-username/task-manager-cicd/actions/workflows/ci.yml/badge.svg)](https://github.com/votre-username/task-manager-cicd/actions)
```

---

## 🔧 Configuration du projet

### Variables d'environnement (`.env`)

```env
APP_NAME=TaskManager
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Composer Scripts

Raccourcis disponibles dans `composer.json` :

```bash
composer test           # Exécute PHPUnit
composer stan          # Lance PHPStan
composer lint          # Vérifie le format du code
```

---

## 📝 Workflow Git et Contribution

### Modèle de branching

```
main (production)
├── develop (intégration)
│   ├── feature/auth-users
│   ├── feature/task-filters
│   ├── fix/task-deletion-bug
│   └── ...
```

### Conventional Commits

Utilisez un format de commit standardisé :

```bash
git commit -m "feat: ajouter filtrage des tâches par priorité"
git commit -m "fix: corriger le bug de suppression"
git commit -m "docs: mettre à jour le README"
git commit -m "test: ajouter tests pour TaskController"
```

**Format** : `<type>(<scope>): <message>`

Types acceptés : `feat`, `fix`, `docs`, `test`, `refactor`, `perf`, `chore`

### Process de contribution

1. **Créez une branche** depuis `develop`
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/ma-fonctionnalite
   ```

2. **Développez votre feature**
   ```bash
   git add .
   git commit -m "feat: description de ma feature"
   ```

3. **Poussez vers le serveur**
   ```bash
   git push origin feature/ma-fonctionnalite
   ```

4. **Ouvrez une Pull Request**
   - Vers la branche `develop`
   - Décrivez vos changements
   - Attendez le code review

5. **Après approbation**
   ```bash
   git checkout develop
   git pull origin develop
   git merge feature/ma-fonctionnalite
   git push origin develop
   ```

---

## 📚 Routes et Endpoints

### Routes Web

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/tasks` | Lister toutes les tâches |
| GET | `/tasks/create` | Formulaire de création |
| POST | `/tasks` | Créer une tâche |
| GET | `/tasks/{id}` | Voir les détails |
| GET | `/tasks/{id}/edit` | Formulaire d'édition |
| PUT | `/tasks/{id}` | Mettre à jour |
| DELETE | `/tasks/{id}` | Supprimer |

Voir `routes/web.php` pour le détail complet.

---

## 🐛 Dépannage

### Erreur : "Class 'App\Models\Task' not found"

```bash
php artisan migrate:reset
php artisan migrate
```

### Erreur : "No application encryption key has been specified"

```bash
php artisan key:generate
```

### Les assets ne se mettent pas à jour

```bash
npm run build
# ou
npm run dev
```

### Erreur de permission sur `storage/`

```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## 📊 Statistiques du projet

- **Langage** : PHP 8.2+ avec Laravel 11
- **Frontend** : Blade Templates + Vue.js (optionnel)
- **Tests** : PHPUnit
- **Couverture de code** : À mettre à jour
- **Analyses** : PHPStan Level 5
- **Licence** : MIT

---

## 📖 Documentation supplémentaire

- 📘 [Documentation Laravel officielle](https://laravel.com/docs)
- 🧪 [PHPUnit Documentation](https://phpunit.de/documentation.html)
- 🔍 [PHPStan Handbook](https://phpstan.org/user-guide)
- 🔄 [GitHub Actions Docs](https://docs.github.com/actions)
- 📝 [Conventional Commits](https://www.conventionalcommits.org/)

---

## 👥 Contribution

Les contributions sont bienvenues ! Veuillez :

1. Forker le repository
2. Créer une branche pour votre feature (`git checkout -b feature/AmazingFeature`)
3. Committer vos changements (`git commit -m 'feat: Add some AmazingFeature'`)
4. Pousser vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

### Code de conduite

Tous les contributeurs doivent respecter l'intégrité du code et la professionnalité lors des interactions.

---

## 📄 Licence

Ce projet est licencié sous la [Licence MIT](LICENSE). Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 📧 Support

Pour toute question ou problème :
- 📤 Ouvrez une issue sur GitHub
- 💬 Discutez dans les discussions du projet
- 📨 Contactez l'équipe de développement

---

<div align="center">

**Fait avec ❤️ pour la communauté DevOps Laravel**

Étoile ⭐ ce projet si vous l'avez trouvé utile !

</div>
