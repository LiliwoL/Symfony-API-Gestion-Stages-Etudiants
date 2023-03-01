# Installation

Installation de Symfony en version minimale (aucune dépendance, il faudra tout installer manuellement)

```bash
symfony new API-Gestion-Stages-Etudiants --version="5.4"
```

## Dans le cas d'une API servie par un serveur Apache

Installez la dépendance **apache-pack**

```bash
composer require symfony/apache-pack
```

# Configuration

Créez le fichier .env dans lequel on va spécifier un secret, mais surtout l'emplacement de la base de données sqlite.

## Module sqlite dans PHP

Pour vérifier que le module sqlite3 est bien installé avec PHP:

```bash
php -m
```

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


```bash
symfony console make:controller ApiStudentController
```

## Normalisation

composer require symfony/serializer-pack

On va renvoyer un objet de la base de données au format JSON

