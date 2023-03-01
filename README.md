# Installation

```
symfony new API-Gestion-Stages-Etudiants --version="5.4"
```

# Configuration

Créez le fichier .env

# Création des entités

## Dépendance

```
composer require symfony/maker-bundle --dev
composer require doctrine/annotations
composer require orm
```

## Création

```bash
symfony console make:entity
```

# Migrations de la structure

On va appliquer à la base les modifications

```bash
symfony console make:migration
symfony console doctrine:migration:migrate
```

# Import des données avec Sqlite

# Création des Contrôleurs

## Dépendance

```bash
composer require doctrine/annotations
```

```bash
symfony console make:controller ApiStudentController
```

## Normalisation

composer require symfony/serializer-pack

On va renvoyer un objet de la base de données au format JSON

