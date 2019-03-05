# Presentation

testTechniqueGTPConseil-EM consiste en une application à plusieurs interfaces visuelles pour la gestion des tâches *employés*

# Requirements

Connexion internet
CDN Utilisés :

- JQuery : 3.3.1
- Bootstrap : 4.0
- Bootstrap : JS
- Datatables + bootstrap : 1.10.19

Versions :

- PHP  : 7.3.2
- mysql : 5.7.24

# Mise en place du projet :

Pour installer le projet sur votre machine, vous aurez besoin de fork ce repository et de le clone en local.

:warning: Pensez à bien cloner le projet dans votre serveur web préféré (docker, homestead, apache, nginx ...) configuré avec les versions précedemment citées de PHP et SQL.

Une fois le projet installé, pour la mise en place de la base de données, vous aurez besoin de configurer le fichier .env situé à la racine de votre projet avec la ligne suivante (le choix du nom de la base de données vous reviens)

`
...
DATABASE_URL=mysql://root:""@127.0.0.1:3306/testGTPConseil-EstebanMANSART
`

Le fichier étant configuré, nous allons pouvoir créer la base de données et la remplir de fausses données (faker :heart: !)

Lancez successivement ces ligne de commande dans votre interpréteur de commande préféré (tout en veillant à bien être situé dans le projet en question).


<pre>
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

- emp0@gtp-conseil.com
- emp1@gtp-conseil.com
- ...
- emp8@gtp-conseil.com

*Login des admins :*

- admin0@gtp-conseil.com
- admin1@gtp-conseil.com
- ...
- admin3@gtp-conseil.com

_Le nombre d'employés et d'admins générés est géré dans src/DataFixtures/BaseFixtures::\_\_construct()\__
