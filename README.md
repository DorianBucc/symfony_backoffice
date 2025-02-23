## Fonctionnalité

Gestion des utilisateurs : CRUD utilisateurs (Admin uniquement).

Voter pour restreindre l'accès.

Gestion des produits : CRUD produits (Admin uniquement/ visibilité par tous les users).

Trier par prix décroissant.

Export CSV des produits.

Import CSV via commande Symfony.

Gestion des clients : CRUD clients (Admin/Gestionnaire).

Validation email et noms.

## Non-fontionnel

Commande Symfony pour ajouter un client : Ne fonctionne pas

Le test du service d'exportation fonctionne à moitié:
- La commande pour lancer le test  `php bin/phpunit tests/Service/ProductExportServiceTest.php`


## Installation en local

1. Cloner le projet
2. Installer PHP >= 8.2 et Composer (Sur votre machine utiliser XAMPP pour windows, MAMP pour mac ou LAMP pour linux bien prendre la version PHP 8.2)
3. Installer les dépendances du projet avec la commande `composer install`
4. Modifier le fichier `.env` à la racine du projet et ajouter la configuration de votre base de données
(default: `DATABASE_URL="mysql://root:root@127.0.0.1:3306/app?serverVersion=8.0.39&charset=utf8mb4"`)
5. Créer la base de données avec la commande `php bin/console doctrine:database:create`
6. Créer les tables de la base de donnée avec la commande `php bin/console doctrine:schema:create`
6. Charger les fixture dans la base de donnée avec la commande `php bin/console doctrine:fixtures:load` et `yes`

## Initialisation de votre IDE
### Visual Studio Code

1. Ouvrir le projet dans Visual Studio Code
2. Installer les extensions pour PHP, Twig et Symfony

## Utilisation

Si vous utilisez symfony server:
    la commande qui permet de lancer le serveur : `symfony server:start`
Lien de la page d'accueil `localhost:8000/`

Si vous utilisez WampServer vous devriez commenté la ligne 2 et décommenté la ligne 3 du fichier assets/bootstrap.js 
car les liens sur WampServer son pas les mêmes que sur symfony server

### User :
1. admin@example.com  mdp : password
2. manager@example.com  mdp : password
3. user@example.com  mdp : password

