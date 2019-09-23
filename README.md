# Presentation

testTechniqueGTPConseil-EM consiste en une application à plusieurs interfaces visuelles pour la gestion des tâches *employés*

# Pré-requis

Connexion internet
CDN Utilisés :

- JQuery : 3.3.1 [Downloads](http://jquery.com/download/)
- Bootstrap : 4.3 [Downloads](https://getbootstrap.com/docs/4.3/getting-started/download/)
- Datatables + bootstrap : 1.10.19 [Downloads](https://datatables.net/download/)
- Fullcalendar : 3.13.0 [Downloads](https://fullcalendar.io/download)
- Moment : 2.24.0 [Downloads](http://momentjs.com/)

Versions :

- PHP  : 7.3.2 [(Windows)](https://windows.php.net/download#php-7.3) [(Other OS)](http://php.net/get/php-7.3.2.tar.gz/from/a/mirror)
- mysqld : 5.7.24 [(Any support)](https://dev.mysql.com/downloads/mysql/5.7.html)
- Symfony : 4.2.3 [Install doc](https://symfony.com/doc/current/setup.html)

# Mise en place du projet :

Pour installer le projet sur votre machine, vous aurez besoin de fork ce repository et de le clone en local.

:warning: Pensez à bien cloner le projet dans votre serveur web préféré (docker, homestead, apache, nginx ...) configuré avec les versions précedemment citées de PHP et SQL.

Une fois le projet installé, pour la mise en place de la base de données, vous aurez besoin de configurer le fichier .env situé à la racine de votre projet avec la ligne suivante (le choix du nom de la base de données vous revient)

`
...
DATABASE_URL=mysql://root:""@127.0.0.1:3306/testGTPConseil-EstebanMANSART
`

Le fichier étant configuré, nous allons pouvoir créer la base de données et la remplir de fausses données (faker :heart: !)

Lancez successivement ces lignes de commandes dans votre interpréteur de commande préféré (tout en veillant à bien être situé dans le répertoire d'un projet en question).


<pre>
// Installation des dépendances via composer
composer install

// Création de la base de données vide
php bin/console doctrine:database:create

// Création des fichiers de migration
php bin/console make:migration

// Migration des données
php bin/console doctrine:migrations:migrate

// Remplissage de la base de données
php bin/console doctrine:fixtures:load
</pre>


And that's it ! :scream:


# Informations :

Les comptes employés et les comptes admin ont tous le même mot de passe : _GTPConseil12345_
*Login des comptes employés :*

- emp0@gtp-conseil.fr
- emp1@gtp-conseil.fr
- ...
- emp8@gtp-conseil.fr

*Login des admins :*

- admin0@gtp-conseil.fr
- admin1@gtp-conseil.fr
- ...
- admin3@gtp-conseil.fr

_Le nombre d'employés et d'admins générés est géré dans src/DataFixtures/BaseFixtures::\_\_construct()_




Test: 
[CLIC](./public/testtoto.md)
