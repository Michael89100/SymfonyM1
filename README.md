# Projet Symfony M1

Ce projet est construit avec le framework Symfony.

Il présente un site d'inscription à des forums des métiers.

## Prérequis

-   PHP 7.4 ou supérieur
-   Composer
-   Symfony CLI
-   Une base de données MySQL

OU

-   Docker

## Installation

### Exécution sur la machine

1. Clonez le dépôt Git. `git clone https://github.com/Michael89100/SymfonyM1.git`
2. Se déplacer dans le dossier app `cd SymfonyM1/app`
3. Exécutez `composer install` pour installer les dépendances.
4. Créer un fichier `.env.dev.local` et renseigner les informations de connexion de votre BDD.

```ini
# Exemple pour une BDD exécutée sous Wamp
DATABASE_URL="mysql://root:root@127.0.0.1:3306/nom_de_la_bdd"
```

5. Exécutez `php bin/console doctrine:database:create` pour créer la base de données.
6. Exécutez `php bin/console doctrine:migrations:migrate` pour exécuter les migrations.
7. Exécutez `php bin/console doctrine:fixtures:load` pour remplire la bdd de DataFixtures
8. Exécutez `symfony server:start` pour démarrer le serveur de développement.
9. Se rendre à l'adresse http://localhost:8000

### Exécution avec docker-compose

1. Clonez le dépôt Git. `git clone https://github.com/Michael89100/SymfonyM1.git`
2. Se déplacer dans le dossier SymfonyM1 `cd SymfonyM1`
3. Exécutez `docker compose up -d`
4. Exécutez `docker compose exec php /bin/bash`
5. Exécutez `composer install`
6. Créer un fichier `.env.dev.local` et renseigner les informations de connexion de votre BDD.

```ini
# Exemple pour une BDD exécutée sous Wamp
DATABASE_URL="mysql://root:root@mariadb:3306/nom_de_la_bdd"
```

7. Exécutez `php bin/console doctrine:database:create` pour créer la base de données.
8. Exécutez `php bin/console doctrine:migrations:migrate` pour exécuter les migrations.
9. Exécutez `php bin/console doctrine:fixtures:load` pour remplire la bdd de DataFixtures
10. Se rendre à l'adresse http://localhost

## Routes

Accueil : http://localhost (Docker) ou http://localhost:8000 (php local)

Admin : http://localhost/admin (Docker) ou http://localhost:8000/admin (php local)

mailcatcher : http://localhost:1080

phpmyadmin : http://localhost:8080

## API

Récupération de la liste des ateliers et du nombre de places :

http://localhost/api/v1
