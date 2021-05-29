Projet 7 - Créez un web service exposant une API
================================================
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ce2fe25e99fb4451b60bf4f17a50863f)](https://www.codacy.com/gh/FrancoisNimpagaritse/BileMo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=FrancoisNimpagaritse/BileMo&amp;utm_campaign=Badge_Grade)

Dans mon parcours de Développeur d'application PHP/Symfony chez OpenClassrooms j'ai créé une API REST avec le framework Symfony. 

Informations sur l'environnement et outils utilisés durant le développement
--------------------------------------------------------------------------- 
* PHP 7.2.8
* Symfony 5.1
* MySQL 5.7.19

Installation
-------------- 

1. Clonez ou téléchargez le repository GitHub dans le dossier voulu :
    git clone https://github.com/FrancoisNimpagaritse/BileMo
2. Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier .env.local qui devra être crée à la racine du projet en réalisant une copie du fichier .env.

3. Téléchargez et installez les dépendances de l'application avec Composer :

    composer install

4. Créez la base de données et importez le fichier bilemo.sql présent à la racine (dans ce cas sauter l'étape 5) ou, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
    php bin/console doctrine:database:create
    
5. Créez les différentes tables de la base de données en appliquant les migrations :
    php bin/console doctrine:migrations:migrate

6. L'API est installé et vousn pouvez consulter sa documlentation technique sur 

7. Vous pouvez commencer à exécuter des requêtes http avec postman ou un autre outil pour tester l'API !
8. Accédez à la documentation de l'API : 127.0.0.1:8000/api/doc (selon l'adresse d'hébergement de l'API)

9. Se connecter et obtenir un token : Requête POST sur http://127.0.0.1:8000/login_check

{"username": "franimpa@yahoo.fr", "password": "password"}

Bon travail
-------------