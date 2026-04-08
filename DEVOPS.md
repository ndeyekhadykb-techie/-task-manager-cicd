# DevOps - Task Manager CI/CD Pipeline

**Documentation Technique du Pipeline d'Intégration Continue et Déploiement**

> **Maintenance** : Mise à jour: avril 2026 | Projet : Task Manager CI/CD | Version Pipeline : 1.0

---

## 📑 Table des matières

1. [Architecture globale](#1-architecture-globale)
2. [Pipeline CI/CD détaillée](#2-pipeline-cicd-détaillée)
3. [Stratégie de branches Git](#3-stratégie-de-branches-git)
4. [Processus de déploiement](#4-processus-de-déploiement)
5. [Configuration Docker](#5-configuration-docker)
6. [Outils et composants](#6-outils-et-composants)
7. [Difficultés et solutions](#7-difficultés-et-solutions)
8. [Maintenance et monitoring](#8-maintenance-et-monitoring)

---

## 1. Architecture globale

### Vue d'ensemble

```
┌─────────────────────────────────────────────────────────────────┐
│                      Git Repository (GitHub)                    │
│                                                                   │
│  main (prod) ←── develop ←── feature/* / fix/* / docs/*         │
└────────────────┬──────────────────────────────────────────────┘
                 │
                 ├─→ Webhook Trigger
                 │
┌────────────────▼──────────────────────────────────────────────┐
│                   GitHub Actions (CI/CD)                       │
│                                                                  │
│  ┌──────────────────────────────────────┐                      │
│  │         Job: Backend Tests            │                      │
│  │  - Setup PHP 8.3                      │                      │
│  │  - Install Composer                   │                      │
│  │  - PHPStan Analysis (Static)          │                      │
│  │  - PHP CS Fixer (Code Style)          │                      │
│  │  - PHPUnit Tests + Coverage (70%)     │                      │
│  │  - MySQL Service                      │                      │
│  └──────────────────────────────────────┘                      │
│                    │                                             │
│  ┌─────────────────▼──────────────────────┐                    │
│  │      Job: Frontend Build                │                    │
│  │  - Setup Node.js 20                     │                    │
│  │  - npm ci (dependencies)                │                    │
│  │  - npm run build (Vite)                 │                    │
│  │  - Upload Artifacts                     │                    │
│  └──────────────────────────────────────┘                      │
│                                                                  │
│     Status: ✅ All Pass → Deploy Ready                         │
│              ❌ Any Fail → Block Merge                         │
└────────────────┬──────────────────────────────────────────────┘
                 │
                 ├─→ Deploy to Staging (optionnel)
                 │
                 └─→ Deploy to Production (manual approval)
```

### Flux de données

```
Developer
    │
    ├─→ Git commit (Conventional Commits)
    │
    ├─→ Git push to feature branch
    │
    ├─→ Open Pull Request (PR)
    │
    ├─→ GitHub Actions Trigger
    │       ├─→ Backend Tests (PHP, DB)
    │       ├─→ Frontend Build (Node.js)
    │       └─→ Code Quality Checks
    │
    ├─→ Code Review + Approval
    │
    ├─→ Merge to develop → Main Build Runs
    │
    └─→ Manual Deployment to Production
```

---

## 2. Pipeline CI/CD détaillée

### 2.1 Configuration du workflow (`.github/workflows/ci.yml`)

**Trigger Events:**
- ✅ `push` sur branche `main`
- ✅ `pull_request` vers branche `main`

### 2.2 Job 1: Backend Tests

#### Environnement
- **OS** : `ubuntu-latest`
- **PHP** : 8.3 (matrix pour évolution future)
- **Database** : MySQL 8.0 (service containérisé)

#### Étapes d'exécution

##### Étape 1: Checkout
```yaml
- name: Checkout code
  uses: actions/checkout@v4
```
- Clone le repository avec l'historique Git

##### Étape 2: Setup PHP
```yaml
- name: Setup PHP 8.3
  uses: shivammathur/setup-php@v2
  with:
    php-version: 8.3
    extensions: mbstring, pdo_mysql, bcmath, gd
    coverage: xdebug  # Pour la couverture de code
```

**Extensions requises :**
| Extension | Raison |
|-----------|--------|
| `mbstring` | Gestion des chaînes multibyte (Laravel) |
| `pdo_mysql` | Connexion à MySQL |
| `bcmath` | Calculs haute précision (monnaies, etc.) |
| `gd` | Manipulation d'images (optionnel) |
| `xdebug` | Couverture de code (tests) |

##### Étape 3: Cache Composer
```yaml
- name: Configure cache for vendor/
  uses: actions/cache@v3
  with:
    path: vendor
    key: composer-${{ hashFiles('composer.lock') }}
    restore-keys: composer-
```
**Objectif** : Économiser 30-50% du temps de CI (évite réinstall des dépendances)

##### Étape 4: Installation Composer
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
```

**Flags :**
- `--no-interaction` : Mode non-interactif (CI)
- `--prefer-dist` : Télécharge les archives (plus rapide)
- `--optimize-autoloader` : Produit un autoloader optimisé

##### Étape 5: Configuration environnement
```bash
cp .env.example .env
php artisan key:generate
# Configuration BD pour les tests
sed -i 's/DB_DATABASE=.*/DB_DATABASE=laravel/' .env
```

##### Étape 6: Analysis - PHPStan
```bash
vendor/bin/phpstan analyse
```
- **Niveau** : 5 (strict)
- **Détecte** : Erreurs de type, variables non-déclarées, bugs potentiels
- **Temps** : ~10-15s

##### Étape 7: Code Style - PHP CS Fixer
```bash
vendor/bin/php-cs-fixer fix --diff
```
- **Norme** : PSR-12
- **Vérifie** : Indentation, imports, espaces, trailing commas
- **Action** : Si besoin, applique les corrections

##### Étape 8: Tests avec couverture
```bash
php artisan test --coverage --min=70
```

**Paramètres :**
- `--coverage` : Génère un rapport de couverture
- `--min=70` : **Bloc le merge si < 70%** de couverture

**Qu'est-ce qui est testé ?**
```
Couverture de code
├─ Tests unitaires (Unit/)
│  └─ Logique métier isolée
├─ Tests fonctionnels (Feature/)
│  └─ Workflows complets
└─ Rapport détaillé
   ├─ Classes couvertes
   ├─ Méthodes couvertes
   └─ Lignes couvertes
```

### 2.3 Job 2: Frontend Build

#### Environnement
- **OS** : `ubuntu-latest`
- **Node.js** : 20 LTS

#### Étapes

##### Étape 1: Node.js Setup
```yaml
uses: actions/setup-node@v4
with:
  node-version: '20'
```

##### Étape 2: Cache npm
```yaml
path: ~/.npm
key: ${{ runner.os }}-node-${{ hashFiles('package-lock.json') }}
```

##### Étape 3: Install & Build
```bash
npm ci                    # Installation stricte
npm run build            # Vite build
```

**Vite :** Build tool moderne sous Blade Templates
- Minification CSS/JS
- Gestion des assets statiques
- Source maps en dev

##### Étape 4: Upload Artifacts
Les fichiers compilés (`public/build/`) sont archivés pour téléchargement/déploiement

---

## 3. Stratégie de branches Git

### 3.1 Modèle GitFlow

```
main (production)
├─ Tag: v1.0.0, v1.0.1, v1.1.0, etc.
│
└─ develop (intégration)
   │
   ├─→ feature/user-authentication
   │   └─→ PR vers develop
   │
   ├─→ feature/task-filters
   │   └─→ PR vers develop
   │
   ├─→ fix/task-deletion-bug
   │   └─→ PR vers develop
   │
   ├─→ docs/update-readme
   │   └─→ PR vers develop
   │
   └─→ release/1.1.0
       └─→ PR vers main + tag
```

### 3.2 Nommage des branches

**Format :** `<type>/<description>`

| Type | Usage | Exemple |
|------|-------|---------|
| `feature/` | Nouvelle fonctionnalité | `feature/oauth-google` |
| `fix/` | Correction de bug | `fix/session-timeout-issue` |
| `docs/` | Documentation | `docs/api-endpoints` |
| `test/` | Tests | `test/task-controller-coverage` |
| `refactor/` | Refactorisation | `refactor/task-model-optimize` |
| `perf/` | Performance | `perf/database-queries-optimize` |
| `chore/` | Maintenance | `chore/upgrade-dependencies` |

### 3.3 Process branching

#### Créer une branche

```bash
# Sync avec develop
git checkout develop
git pull origin develop

# Créer branche
git checkout -b feature/ma-fonctionnalite
```

#### Trabail sur la branche

```bash
# Edits & commits
git add .
git commit -m "feat: description courte"
git push origin feature/ma-fonctionnalite
```

#### Pull Request

1. **Créer PR sur GitHub**
   - Base: `develop`
   - Compare: `feature/ma-fonctionnalite`
   - Description: Gitmoji + détails

2. **CI/CD tourne automatiquement**
   - Tests, linting, couverture

3. **Code Review**
   - Minimum 1 approbation requise
   - Discussions / demande de changements

4. **Merge**
   - Rebaser ou squash pour historique propre
   - Supprimer la branche

#### Merge vers main

Après approbation sur develop :

```bash
git checkout main
git pull origin main
git merge --no-ff develop
git tag -a v1.1.0 -m "Release version 1.1.0"
git push origin main --tags
```

---

## 4. Processus de déploiement

### 4.1 Environnements

```
Development (Local)
    ↓
Staging (Test - optionnel)
    ↓
Production (Live)
```

### 4.2 Déploiement manuel vers production

**Prérequis :**
- ✅ Branche `main` à jour
- ✅ Tous les tests passent
- ✅ Code review approuvé
- ✅ Tag de version créé

**Processus (optionnel - à implémenter)**

#### Option A: Manual SSH Deploy

```bash
# Sur le serveur de production
cd /var/www/task-manager
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader

# Database migrations
php artisan migrate --force

# Cache clear & compile
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Frontend
npm run build

# Service restart
systemctl restart php-fpm
```

#### Option B: GitHub Actions Auto-Deploy

```yaml
name: Auto Deploy to Production
on:
  push:
    branches: [main]
    
jobs:
  deploy:
    runs-on: ubuntu-latest
    if: github.event_name == 'push'
    
    steps:
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.PROD_HOST }}
          username: ${{ secrets.PROD_USER }}
          key: ${{ secrets.PROD_SSH_KEY }}
          script: |
            cd /var/www/task-manager
            git pull origin main
            composer install --no-dev
            php artisan migrate --force
            php artisan cache:clear
            docker-compose restart      # ou systemctl restart
```

### 4.3 Rollback en cas de problème

```bash
# Revenir à la version précédente
git revert <commit-hash>
git push origin main

# Ou reset brutal (dangereux!)
git reset --hard <tag-ancien>
git push -f origin main
```

---

## 5. Configuration Docker

### 5.1 Pourquoi Docker?

**Bénéfices :**
| Bénéfice | Détail |
|----------|--------|
| **Isolation** | Dev = Prod (même versioning) |
| **Scalabilité** | Orchestration multi-containers |
| **CI/CD** | Environments consisten |
| **Versioning** | Images taggées par version |
| **Déploiement** | Fast, reliable, repeatable |

### 5.2 Architecture proposée

```
docker-compose.yml
├── app (PHP 8.3 + Laravel)
│   ├── Expose: 8000
│   ├── Volumes: ./app:/app
│   └── Depends on: mysql, redis
│
├── mysql (Database)
│   ├── Version: 8.0
│   ├── Port: 3306
│   └── Volumes: ./storage/mysql:/var/lib/mysql
│
├── redis (Cache)
│   ├── Version: 7
│   ├── Port: 6379
│   └── Volumes: ./storage/redis:/data
│
├── nginx (Reverse Proxy)
│   ├── Port: 80, 443
│   ├── Config: ./docker/nginx.conf
│   └── Proxy to app:8000
│
└── phpmyadmin (Admin - Dev only)
    └── Port: 8080
```

### 5.3 Exemple Dockerfile

```dockerfile
# Dockerfile
FROM php:8.3-fpm

# Extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["php-fpm"]
```

### 5.4 docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: task-manager-app
    working_dir: /app
    volumes:
      - .:/app
    ports:
      - "8000:8000"
    environment:
      - DB_CONNECTION=mysql
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=task_manager
      - DB_USERNAME=laravel
      - DB_PASSWORD=secret
      - CACHE_DRIVER=redis
      - REDIS_HOST=redis
    depends_on:
      - mysql
      - redis
    networks:
      - app-network

  mysql:
    image: mysql:8.0
    container_name: task-manager-mysql
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: task_manager
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - app-network

  redis:
    image: redis:7-alpine
    container_name: task-manager-redis
    ports:
      - "6379:6379"
    volumes:
      - redis-data:/data
    networks:
      - app-network

  nginx:
    image: nginx:alpine
    container_name: task-manager-nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/app
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - app-network

volumes:
  mysql-data:
  redis-data:

networks:
  app-network:
    driver: bridge
```

### 5.5 Utilisation

```bash
# Build images
docker-compose build

# Start services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Access app
http://localhost:8000
http://localhost:8080  # phpmyadmin
```

---

## 6. Outils et composants

### 6.1 Stack Backend

#### PHP 8.3
- **Support LTS** jusqu'à décembre 2026
- **JIT Compilation** pour performance 30% mieux
- **Union Types** et améliorations typage
- **Fibers** pour async/await

#### Laravel 13
- **Framework MVC** moderne
- **Eloquent ORM** type-safe
- **Queue system** pour jobs asynchrones
- **Broadcasting** pour temps réel
- **Ecosystem** (Sanctum, Breeze, Sail)

#### Composer
- **Gestionnaire de paquets** PHP
- **Autoloading PSR-4**
- **Scripts personnalisés** (setup, test, etc.)

### 6.2 Stack Frontend

#### Node.js 20 LTS
- **Runtime JavaScript** côté serveur
- **npm** pour les dépendances
- **Support LTS** jusqu'à 2026

#### Vite
- **Build tool** moderne pour assets
- **HMR** (Hot Module Replacement) en dev
- **Code splitting** automatique
- **Minification** production

#### Blade Templates
- **Template engine** Laravel natif
- **Directivess** (@if, @foreach, etc.)
- **Composants réutilisables**

### 6.3 Testing Stack

#### PHPUnit 12
- **Framework de test** PHP standard
- **Fixtures & Mocks**
- **Code Coverage** (Xdebug)
- **Config** : `phpunit.xml`

**Exemple test :**
```php
public function test_user_can_create_task()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->post('/tasks', [
            'title' => 'Mon titre',
            'description' => 'Description',
            'priority' => 'high',
        ]);
    
    $this->assertDatabaseHas('tasks', ['title' => 'Mon titre']);
    $response->assertRedirect('/tasks');
}
```

### 6.4 Code Quality Tools

#### PHPStan
- **Analyse statique** stricte (Niveau 5)
- **Détecte** : types incorrects, variables non-déclarées
- **Config** : `phpstan.neon`

```bash
# Analyse
./vendor/bin/phpstan analyse

# Output: 0 errors - OK ✓
```

#### PHP CS Fixer
- **Correcteur de style** automatique
- **Norme** : PSR-12 + custom rules
- **Config** : `.php-cs-fixer.php`

```bash
# Check
./vendor/bin/php-cs-fixer fix --diff

# Auto-fix
./vendor/bin/php-cs-fixer fix
```

**Rules appliquées :**
- PSR-12 (spacing, indentation)
- Short array syntax `[]`
- Imports ordered alphabetically
- No unused imports
- Trailing commas en multiline

#### Larastan
- **Extension PHPStan** pour Laravel
- **Valide** les Facades, Collections, Eloquent
- **Améliore** la détection d'erreurs Laravel-spécifiques

---

## 7. Difficultés et solutions

### 7.1 Coverage < 70%

**Problème:** Tests insuffisants, merge bloqué

**Solutions:**
1. Examiner le rapport de coverage (`--coverage`)
2. Identifier les branches non-testées
3. Ajouter tests manquants :

```php
// Avant: coverage 45%
public function getTaskStatus() {
    if ($this->completed) return 'Done';
    return 'Pending';
}

// Après: coverage 100%
public function test_get_task_status_when_completed() {
    $task = new Task(['completed' => true]);
    $this->assertEquals('Done', $task->getTaskStatus());
}

public function test_get_task_status_when_pending() {
    $task = new Task(['completed' => false]);
    $this->assertEquals('Pending', $task->getTaskStatus());
}
```

**Outils utiles:**
```bash
# Coverage détaillé
php artisan test --coverage --min=80

# HTML report
php artisan test --coverage-html=coverage

# Filter tests
php artisan test --filter TaskControllerTest
```

### 7.2 PHPStan Errors

**Problème:** Erreurs de typage strictes (Niveau 5)

**Exemple:**
```
Call to an undefined method App\Models\Task::findOrFail()
Parameter #1 $id of method findOrFail() expects int|string, string given
```

**Solution:**
```php
// ❌ Avant
$task = Task::findOrFail($_GET['id']);  // ID peut être NULL

// ✅ Après
$task = Task::findOrFail((int) $request->input('id'));
```

**Stratégies:**
1. **Typer les propriétés**
   ```php
   class Task extends Model {
       private int $priority = 1;
       protected ?string $description = null;
   }
   ```

2. **Typer les paramètres & retours**
   ```php
   public function getPriority(): string {
       return $this->priority === 'high' ? 'High' : 'Low';
   }
   ```

3. **Ignorer les erreurs inévitables** (rare)
   ```php
   /** @phpstan-ignore-line */
   $config = json_decode($raw, true);
   ```

### 7.3 Database Connection Timeout

**Problème:** MySQL service pas prêt avant les tests

**Solution CI (GitHub Actions):**
```yaml
services:
  mysql:
    image: mysql:8.0
    options: >-
      --health-cmd="mysqladmin ping"
      --health-interval=10s
      --health-timeout=5s
      --health-retries=3
```

**Solution Local (docker-compose):**
```yaml
app:
  depends_on:
    mysql:
      condition: service_healthy
```

### 7.4 npm Build Fails (Missing dependencies)

**Problème:** `npm ci` échoue, cache invalide

**Solutions:**
```bash
# Effacer cache
rm -rf node_modules package-lock.json

# Réinstall strict
npm ci

# Vérifier versions
npm list

# Update dépendances
npm update
```

### 7.5 Slow CI Builds

**Symptôme:** Composer install = 2-3 minutes

**Optimisations:**

| Optim | Gain |
|-------|------|
| Actions/cache (composer) | -30s ⚡ |
| --no-interaction flag | -5s |
| --prefer-dist | -10s |
| Parallel tests | -20s |

```bash
# Tester en paralèlle
php artisan test --parallel

# Cache tout
php artisan config:cache
php artisan route:cache
```

### 7.6 Déploiement avec migrations bloquantes

**Problème:** Migration longue = downtime utilisateurs

**Solution - Blue/Green Deploy:**
```yaml
# 1. Deploy nouvelle version (blue)
# 2. Exécuter migrations
# 3. Basculer traffic (green)
# 4. Garder old version pour rollback
```

```bash
# Avant merge
php artisan migrate:validate

# Migration rollback if needed
php artisan migrate:rollback
```

### 7.7 Secret Management

**Problème:** Credentials en clair dans `.env`

**Solution - GitHub Secrets:**
```yaml
env:
  DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
  API_KEY: ${{ secrets.API_KEY }}
```

Configurer dans: GitHub → Settings → Secrets & Variables → Actions

---

## 8. Maintenance et monitoring

### 8.1 Monitoring CI/CD

**Dashboards à surveiller:**

1. **GitHub Actions**
   - Temps d'exécution des workflows
   - Taux de réussite
   - Alertes sur failures

2. **Code Quality**
   - PHPStan baseline (doit rester stable)
   - Coverage trends
   - Technical debt

### 8.2 Logs à consulter

```bash
# Logs locaux
tail -f storage/logs/laravel.log

# Logs CI (GitHub Actions)
https://github.com/votre-org/task-manager-cicd/actions

# Logs déploiement
journalctl -u php-fpm --follow
sudo tail -f /var/log/nginx/error.log
```

### 8.3 Maintenance périodique

**Hebdomadaire:**
- ✅ Revoir les failed workflows
- ✅ Update composer/npm si patches sécurité
- ✅ Exécuter tests localement avant PR

**Mensuellement:**
- 🔄 Mettre à jour dépendances mineures
- 🔄 Analyser trends de couverture
- 🔄 Vérifier health du pipeline

**Trimestriellement:**
- 🔄 Upgrade versions majeures PHP/Laravel (planifié)
- 🔄 Audit sécurité complet
- 🔄 Performance review du pipeline

### 8.4 Checklist de déploiement

- [ ] Tous les tests passent ✅
- [ ] Coverage ≥ 70% ✅
- [ ] PHPStan 0 errors ✅
- [ ] Code review approuvé ✅
- [ ] Migrations testées localement ✅
- [ ] Rollback plan en place ✅
- [ ] Alerting configuré ✅
- [ ] Backup BD avant déploiement ✅
- [ ] Documentation mise à jour ✅
- [ ] Monitoring actif ✅

---

## 📞 Support et ressources

### Documentation

- 📘 [GitHub Actions Documentation](https://docs.github.com/actions)
- 📗 [PHPStan Handbook](https://phpstan.org/)
- 📙 [PHP CS Fixer Docs](https://cs.symfony.com/)
- 📕 [Laravel Deployment](https://laravel.com/docs/deployment)
- 📓 [Docker & PHP](https://hub.docker.com/_/php)

### Contacts

- **DevOps Lead** : Serigne Diagne
- **Repository** : [task-manager-cicd](https://github.com/votre-org/task-manager-cicd)
- **Issues** : [GitHub Issues](https://github.com/votre-org/task-manager-cicd/issues)

---

<div align="center">

**Mise à jour : Avril 2026**

Pour mettre à jour cette documentation, créer une issue ou PR 💙

</div>
