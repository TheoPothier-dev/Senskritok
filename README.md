# SensKritok — API avec Symfony

J'ai réalisé ce projet dans le cadre du cours de 2 année **Framework PHP : API** ([support de cours](https://alban-crepel.com/cours/mds/php-api)).

SensKritok est une API inspirée de SensCritique. Elle permet de lister des films, livres et jeux, de les noter, de les ajouter à des collections, d'écrire des critiques, de suivre d'autres utilisateurs et de voir leurs notes dans un fil d'actualité.

L'API expose des **ressources en JSON**

---

## Stack technique

| Outil | Version / rôle |
|---|---|
| PHP | >= 8.4 |
| Symfony | 8.1 |
| Doctrine ORM | 3.x, avec Doctrine Migrations |
| Base de données | SQLite (`var/data_dev.db`) |
| Symfony Serializer | sérialisation JSON avec groupes |
| PHPUnit | 13.x, tests fonctionnels |
| Symfony Maker Bundle | génération de code (dev) |

---

## Prérequis

- [PHP](https://www.php.net/) (installation avec `winget` sous Windows ou Homebrew sous macOS)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)
- Git
- *(optionnel)* un IDE avec les plugins Symfony : PHP Intelephense et PHP Namespace Resolver pour VS Code, ou le plugin Symfony pour PhpStorm
- *(optionnel)* l'extension **HttpYac** (VS Code) pour exécuter le fichier `api.http`

---

## Installation

```bash
# 1. Cloner le projet puis installer les dépendances
git clone <url-du-repo> senskritok
cd senskritok
composer install

# 2. Créer la base de données SQLite et appliquer les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 3. Lancer le serveur de développement
symfony serve
```

L'API est alors disponible sur **http://127.0.0.1:8000**.

---

## Structure du projet

```
src/
├── Controller/
│   └── MoviesController.php   # Routes de la ressource /movies
├── Entity/
│   ├── Movie.php              # Entité Film
│   └── Category.php           # Entité Catégorie
├── Repository/
│   ├── MovieRepository.php    # Requêtes personnalisées (filtres)
│   └── CategoryRepository.php
└── Migrations/                # Migrations Doctrine
tests/
└── Controller/
    └── MoviesControllerTest.php
api.http                       # Requêtes de test de l'API (HttpYac / PhpStorm)
```

Symfony suit un schéma **Request / Response** :
le client envoie une requête HTTP → le **contrôleur** interroge un **repository** (Doctrine) → les **entités** sont lues ou écrites en base → le contrôleur renvoie une **réponse JSON**.

---

## Modèle de données

### `Movie`

| Champ | Type | Description |
|---|---|---|
| `id` | int | Identifiant auto-incrémenté |
| `title` | string (255) | Titre du film |
| `description` | string (255) | Description |
| `year` | int | Année de sortie |
| `categories` | Collection\<Category\> | Catégories du film |

### `Category`

| Champ | Type | Description |
|---|---|---|
| `id` | int | Identifiant auto-incrémenté |
| `name` | string (255) | Nom de la catégorie |

### Relation

**Movie ⟷ Category** est une relation **ManyToMany** : un film a une ou plusieurs catégories, et une catégorie regroupe un ou plusieurs films. Elle est stockée dans la table de jointure `category_movie`.

### Groupes de sérialisation

Les groupes contrôlent les champs exposés en JSON et évitent les références circulaires entre `Movie` et `Category` :

- `movie:read`: champs renvoyés lors de la lecture d'un film (catégories incluses avec `id` et `name`)
- `movie:write`: champs acceptés en écriture
- `category:read`: lecture d'une catégorie

---

## Endpoints

| Méthode | Route | Description | État |
|---|---|---|---|
| `GET` | `/movies` | Liste les films, avec filtres optionnels `?title=` et `?year=` | ✅ |
| `POST` | `/movies` | Crée un film | ✅ |
| `GET` | `/movies/{id}` | Récupère un film | ⏳ à faire |
| `PATCH` / `PUT` | `/movies/{id}` | Modifie un film | ⏳ à faire |
| `DELETE` | `/movies/{id}` | Supprime un film | ⏳ à faire |

### `GET /movies`

Les paramètres sont optionnels et peuvent se combiner :

| Paramètre | Exemple | Effet |
|---|---|---|
| `title` | `?title=Interstellar` | Filtre sur le titre (correspondance exacte pour l'instant) |
| `year` | `?year=2014` | Filtre sur l'année de sortie |

Les résultats sont triés par titre (ordre alphabétique) et limités à 5.

```http
GET http://127.0.0.1:8000/movies?title=Interstellar&year=2014
```

### `POST /movies`

```http
POST http://127.0.0.1:8000/movies
Content-Type: application/json

{
  "title": "Interstellar",
  "description": "Film de Nolan",
  "year": 2014,
  "categories": [1, 3]
}
```

`categories` contient les **identifiants** de catégories existantes. La réponse renvoie le film créé, sérialisé avec le groupe `movie:read`.

---

## Tester l'API

### Fichier `.http`

Le fichier [`api.http`](api.http) regroupe des requêtes prêtes à l'emploi. On peut l'exécuter avec PhpStorm ou avec l'extension HttpYac de VS Code.

### Tests PHPUnit

```bash
php bin/phpunit
```

---

## Commandes utiles

```bash
# Serveur
symfony serve                                   # serveur de dev sur http://127.0.0.1:8000
php bin/console debug:router                    # liste toutes les routes

# Génération de code (Maker Bundle)
php bin/console make:controller NomController   # nouveau contrôleur
php bin/console make:entity NomEntite           # création / modification d'une entité ou d'une relation

# Base de données (Doctrine)
php bin/console make:migration                  # génère une migration à partir des entités
php bin/console doctrine:migrations:migrate     # applique les migrations
php bin/console doctrine:database:create        # crée la base
php bin/console doctrine:database:drop --force  # supprime la base
php bin/console doctrine:schema:update --force  # met à jour le schéma sans migration (raccourci : d:s:u --force)

# Tests
php bin/phpunit
```

---

## Avancement

### Réalisé

- [x] **Initialisation** : projet Symfony 8.1, Maker Bundle, pack de tests
- [x] **Contrôleurs** : `MoviesController`, routes typées par méthode HTTP, lecture de la requête (`Request`, query params, body JSON)
- [x] **Entités** : `Movie` et `Category`, avec une relation ManyToMany
- [x] Migrations Doctrine sur une base SQLite
- [x] Groupes de sérialisation (`movie:read`, `movie:write`, `category:read`)
- [x] `POST /movies` : création d'un film avec ses catégories
- [x] `GET /movies` : listing avec filtres `title` et `year` combinables
- [x] Fichier `api.http` pour tester les routes

### À faire

- [ ] Compléter le CRUD : `GET /movies/{id}`, `PATCH`/`PUT /movies/{id}`, `DELETE /movies/{id}`
- [ ] Pagination avec `?page=` et `?limit=` (classe `Paginator` et `PaginationDTO`)
- [ ] Validation des entrées avec des DTO (`#[MapQueryString]`, `#[MapRequestPayload]`, contraintes `Assert`) et un code `422` en cas d'erreur
- [ ] Codes HTTP plus précis (`201 Created` à la création, `404` si une catégorie ou un film est introuvable)
- [ ] Compléter les tests PHPUnit (`MoviesControllerTest`)
- [ ] Ressources suivantes : livres, jeux, notes, critiques, collections, utilisateurs, abonnements et fil d'actualité
- [ ] Authentification JWT (`Authorization: Bearer <token>`)
