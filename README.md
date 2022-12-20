Pour récupérer le projet : 

Créer (cloner) le projet sur Commander depuis son URL Git, pour cela :
	
	a – Se mettre dans Commander sur le répertoire htdocs :
	cd ../../
	cd c:/xampp/htdocs/

	b - Il faut cloner le projet pour créer le dossier Extranet dans htdocs, taper la ligne de 	commande suivante :

	git clone https://github.com/Extranet-WF3/Extranet.git
    
    (adresse URL du projet qui se trouve sur votre site GitHub dans la page du projet Extranet, cliquer dans l'icône « Code » sur GitHub)

Remarque : Git demande un Token d'authentification. Pour créer un Token aller sur votre page personnelle GitHub et cliquer en haut à droite sur votre photo ou avatar, puis cliquer sur « Settings ». Dans la nouvelle page aller dans « Developer Settings » sur la side-bar de gauche. Dans la nouvelle page aller dans « Personal Access Tokens ». Dans la nouvelle page cliquer sur l'icone « Generate new Token ». Dans la fenêtre « Note », entrer un nom. Choisir le nombre de jours de validité. Choisir les permissions du Token (choisir « Repo »). Note : bien noter quelque part le nom que vous avez choisi, ainsi que le Token (on ne peut plus le récupérer). Cliquer sur l'icône « Generate Token ».

	c - Pour récupérer les répertoires manquants du projet

		se mettre sur le répertoire du projet :
		cd ../../
		cd c:/xampp/htdocs/Extranet

		On va d'abord travailler sur la branche principale (Main ou Master)

		taper la ligne de commande :

		composer install

		S'il y a un blocage au niveau du composer install à cause de la version de PHP faire :
		
		composer update

	d – Créer la base de données et récupérer les tables du projet :

	Ne pas oublier de lancer le serveur Apache et MySQL dans XAMPP.

Pour configurer la base de données, on crée un fichier env.local (faire un copie de env.env et le renommer .env.local, supprimer l'ensemble des lignes sauf la ligne 30 et la reprendre :

	la ligne doit être la suivante :

DATABASE_URL="mysql://root:@127.0.0.1:3306/extranet?serverVersion=mariadb-10.4.20"

ATTENTION : Vérifier que votre version de mariadb est la bonne : Ici c'est la version 10.4.20


ATTENTION : Dans le fichier .env, il faut commenter la ligne 31 actuellement active pour la désactiver (mettre # devant la ligne)

	e – Créer la base de données en utilisant le nom de la BDD du projet (se mettre sur la branche main)

		php bin/console doctrine:database:create

        Entrer le nom de la base de données suivant : extranet (sans majuscule)

	f – Faire la migration de la BDD pour récupérer la structure de la BDD (tables du projet)

	taper la ligne de commande :

		php bin/console doctrine:migrations:migrate

	Le message suivant apparaît : ATTENTION ! Vous êtes sur le point d'exécuter une migration 	dans la base de données "nomduProjet" qui pourrait entraîner des modifications de schéma et une perte de données. Êtes-vous sûr de vouloir continuer ? (oui/non) [oui] :

	taper yes puis faire entrer

ATTENTION : Par l'intermédiaire de cette procédure, on récupère la structure de la Base de Données (le nom de BDD et la structure des tables créées), mais pas les données de la BDD. Dans ce cas, il faudrait que l'on récupère un export de la BDD et l'importer grâce à phpMyAdmin.

	g – Synchroniser les fixtures (s'il y en a) :

		php bin/console doctrine:fixtures:load

	Le message suivant apparaît : ATTENTION ! Vous êtes sur le point d'exécuter une migration 	dans la base de données "nomduProjet" qui pourrait entraîner des modifications de schéma 	et une perte de données. Êtes-vous sûr de vouloir continuer ? (oui/non) [oui] :

	taper yes puis faire entrer

	h - Récupérer tous les fichiers du projet (qui sont sur la branche main) en tapant la ligne de commande :

	 	git rebase main

Vous pouvez maintenant travailler sur le projet :

    Attention : Penser à changer de branche, vous devez travailler sur votre branche :

    git checkout VotrePrenom





	E-effacer les données dans la BDD

	php bin/console doctrine:fixtures:load

	utiliser les faker et conserver ses données dans la BDD

	php bin/console doctrine:fixtures:load --append"# Projet-Extranet-Symphony" 
