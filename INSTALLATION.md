# Installation - Task Manager

**Guide d'installation rapide (5 minutes)**

---

## 🚀 Installation express

### Prérequis
- Git
- PHP 8.2+ (ou Docker)
- Composer
- Node.js 18+
- MySQL 8.0 (ou SQLite)

---

## Option 1️⃣ : Développement local (XAMPP/Wamp)

### Étape 1: Cloner le projet
```bash
cd C:\xampp\htdocs\
git clone https://github.com/YOUR_USERNAME/task-manager-cicd.git
cd task-manager-cicd
```

### Étape 2: Installer les dépendances
```bash
composer install
npm install
```

### Étape 3: Configurer l'environnement
```bash
copy .env.example .env
php artisan key:generate
```

### Étape 4: Configurer la base de données (si MySQL)
Éditer `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

### Étape 5: Exécuter les migrations
```bash
php artisan migrate
```

### Étape 6: Lancer l'application
```bash
# Terminal 1: PHP server
php artisan serve

# Terminal 2: Frontend (Vite)
npm run dev
```

**Access**: http://localhost:8000

---

## Option 2️⃣ : Docker (Recommandé)

### Étape 1: Cloner le projet
```bash
git clone https://github.com/YOUR_USERNAME/task-manager-cicd.git
cd task-manager-cicd
```

### Étape 2: Lancer Docker
```bash
docker-compose up -d
```

### Étape 3: Installer les dépendances
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

### Étape 4: Setup de la BD
```bash
docker-compose exec app php artisan migrate
```

### Services disponibles

| Service | URL | ID |
|---------|-----|-----|
| App Laravel | http://localhost:8000 | app |
| Nginx proxy | http://localhost:80 | nginx |
| MyPHPAdmin | http://localhost:8080 | phpmyadmin |
| MySQL | localhost:3306 | mysql |
| Redis | localhost:6379 | redis |

**Credentials BD**:
- User: `laravel`
- Password: `secret`
- Database: `task_manager`

---

## ✅ Vérifier l'installation

### Tester les routes
```bash
# Liste des tâches
http://localhost:8000/tasks

# Créer une tâche
http://localhost:8000/tasks/create
```

### Exécuter les tests
```bash
php artisan test
# ou
docker-compose exec app php artisan test
```

### Vérifier la qualité du code
```bash
# PHPStan
./vendor/bin/phpstan analyse

# PHP CS Fixer
./vendor/bin/php-cs-fixer fix --diff
```

---

## 🐛 Troubleshooting

### Erreur: "No application key has been specified"
```bash
php artisan key:generate
```

### Erreur: "Class not found"
```bash
composer dump-autoload
```

### Erreur: "Connection refused" (BD)
- **Local**: Vérifier que MySQL est démarré
- **Docker**: `docker-compose ps` pour vérifier les services

### Erreur: Permissions sur storage/
```bash
chmod -R 775 storage/
```

### Assets ne se chargent pas
```bash
npm run build
```

---

## 📚 Documentation complète

- **README.md** - Guide utilisateur complet
- **DEVOPS.md** - Pipeline CI/CD et Docker
- **PROJECT_CHECKLIST.md** - Liste complète des fichiers

---

## 🎯 Prochaines étapes

1. ✅ Lancer l'app (`php artisan serve`)
2. ✅ Créer une tâche
3. ✅ Exécuter les tests (`php artisan test`)
4. ✅ Lire README.md
5. ✅ Contribuer! 🚀

---

<div align="center">

Besoin d'aide? Consultez DEVOPS.md section "Dépannage"

Happy coding! 🎉

</div>
