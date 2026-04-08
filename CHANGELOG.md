# Changelog

Tous les changements importants de ce projet sont documentés dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
et ce projet respecte [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Documentation DevOps complète (DEVOPS.md)
- CI/CD pipeline avec GitHub Actions
- Support Docker avec Dockerfile et docker-compose.yml
- Tests fonctionnels complets (TaskController CRUD)

### Changed
- Amélioration de la structure du projet
- Centralisation de la configuration

### Fixed
- Corrections mineures de bugs

---

## [1.0.0] - 2026-04-06

### Added
- Initialisation du projet Task Manager
- Modèles CRUD pour les tâches
- Interface web avec Blade templates
- Tests unitaires et fonctionnels
- PHPStan pour l'analyse statique
- PHP CS Fixer pour la normalisation du code
- GitHub Actions CI/CD pipeline
- README complet
- Documentation DevOps

### Features
- ✅ Créer une tâche (titre, description, statut, priorité, date limite)
- ✅ Modifier une tâche
- ✅ Supprimer une tâche
- ✅ Lister les tâches avec filtres par statut
- ✅ Voir les détails d'une tâche
- ✅ Base de données MySQL avec migrations
- ✅ Cache avec Redis (optionnel)
- ✅ Tests PHPUnit complets

---

## Format des mises à jour futures

Lors de la création d'une nouvelle version, respectez ce format :

### Pour une nouvelle version (ex v1.1.0)

```markdown
## [1.1.0] - YYYY-MM-DD

### Added
- Nouvelle feature 1
- Nouvelle feature 2

### Changed
- Modification 1
- Modification 2

### Deprecated
- Feature à supprimer dans v2.0.0

### Removed
- Feature supprimée
- Support PHP 8.1 (maintenant PHP 8.3+ requis)

### Fixed
- Bug fix 1
- Bug fix 2

### Security
- Faille de sécurité corrigée
```

---

## Versioning

Ce projet utilise **Semantic Versioning** :
- **MAJOR** (X.0.0) : Changements incompatibles (breaking changes)
- **MINOR** (1.X.0) : Nouvelles features compatibles
- **PATCH** (1.0.X) : Corrections de bugs

### Exemple
- `1.0.0` → Première version stable
- `1.1.0` → Ajout de nouvelles features
- `1.1.1` → Correction d'un bug
- `2.0.0` → Changements majeurs incompatibles

---

## Types de changements

| Type | Description | Exemple |
|------|-------------|---------|
| **Added** | Nouvelle fonctionnalité | Ajouter filtrage par priorité |
| **Changed** | Changement de comportement existant | Renommer une route |
| **Deprecated** | Feature marquée pour suppression future | Anciennes API |
| **Removed** | Suppression | Support PHP 8.1 |
| **Fixed** | Correction de bug | Correction du logout |
| **Security** | Correction de sécurité | Patch SQL injection |

---

## Historique des releases

### Version 1.0.0 (Stable)
- Date : 2026-04-06
- Support : PHP 8.3+, Laravel 13, MySQL 8.0
- Statut : ✅ Production-ready

### Version 0.1.0 (Alpha - Non publiée)
- Prototype initial
- Statut : Archived

---

## Comment mettre à jour ce fichier

1. **Lors d'un commit significatif (feature/fix/breaking change)**
   ```bash
   git commit -m "feat: ajouter notification par email

   CHANGELOG: Ajouter dans Added - Email notifications pour nouvelles tâches"
   ```

2. **Avant une release (ex v1.1.0)**
   - Créer une section `[1.1.0] - YYYY-MM-DD`
   - Consolider les changements depuis la dernière version
   - Grouper par type (Added, Changed, etc.)
   - Créer un tag Git : `git tag -a v1.1.0 -m "Release v1.1.0"`

3. **Template pour PR description**
   ```
   ## What changed?
   - Feature: Ajouter recherche de tâches

   ## CHANGELOG entry
   Added: Full-text search for tasks
   ```

---

## Liens utiles

- [Git Tags](https://git-scm.com/book/en/v2/Git-Basics-Tagging)
- [Semantic Versioning](https://semver.org/)
- [Keep a Changelog](https://keepachangelog.com/)
- [Résumé des releases GitHub](../../releases)

---

## Support

Pour toute question sur ce CHANGELOG :
- 📖 Consulter la [documentation](README.md)
- 🐛 Ouvrir une [issue](../../issues)
- 💬 Discuter dans les [discussions](../../discussions)

---

<div align="center">

**Dernier mise à jour : 2026-04-06**

Merci de maintenir ce fichier à jour ! 🙏

</div>
