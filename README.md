# API Gestion de stage des étudiants

[toc]

Un projet de découverte de Symfony pour la création d'une API.

# Installation

Installation de Symfony en version minimale (aucune dépendance, il faudra tout installer manuellement)

```bash
symfony new API-Gestion-Stages-Etudiants --version="5.4"
```

# Clonage

```bash
git clone git@github.com:LiliwoL/Symfony-API-Gestion-Stages-Etudiants.git
cd Symfony-API-Gestion-Stages-Etudiants
```

# Installation des dépendances

```bash
composer install
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

On va appliquer à la base de données les déclarations objets.

```bash
symfony console make:migration
symfony console doctrine:migration:migrate
```

# Import des données dans Sqlite

Pour ajouter les données dans sqlite, utilisez les fichiers **sql** placés dans le dossier **DATA**.

# Création des Contrôleurs

```bash
symfony console make:controller ApiStudentController
```

## Normalisation

Afin d'afficher sous forme de tableau un objet, on va recourir à la **normalisation**.
Dans le sens inverse, ce sera de la **sérialization**.

Symfony propose des interfaces performantes pour cela.

```bash
composer require symfony/serializer-pack
```

On va renvoyer un objet de la base de données au format JSON

# Vérification des routes avec Postman

Lancez votre serveur web, ou le serveur intégré à Symfony.
```bash
symfony serve
```

Avec l'utilitaire **Postman**, ouvrez la collection de requêtes située dans **PostmanRequestsCollection**.

Vérifiez les routes existantes.

***
***
***

# Travail à faire

* Améliorez l'existant (vérification, retirer les commandes de debug **dd**,  contenu des réponses)
* Ajouter un contrôleur **Company** sur le même modèle.
* Publiez le résultat dans votre GitHub et fournissez l'url à l'enseignant.