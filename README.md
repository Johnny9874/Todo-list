# Todo-list

Bienvenue sur mon application Todo-list12.

Ici je vais vous guider tout au long du test de A à Z : 

Je vous invite à vous rendre sur ce lien : todo-list12-7ed7bbb86a89.herokuapp.com/html/login.html

Ce lien vous emmenera donc à la page de connexion de l'application, vous serez donc invité à rentrer vos identiants afin de vous connecter à votre seesion. 

Si vous n'avez pas encore crée votre session pas de panique, il suffit de vous inscrire via la page d'inscription. Vous pouvez vous y rendre via ce lien : https://todo-list12-7ed7bbb86a89.herokuapp.com/html/register.html

Vous devriez entré votre nom d'utilisateur, email ainsi qu'un mot de passe. Sachez que si un de ces champ contiennent des informations non valide/conforme un message d'erreur sera affiché après la soumission du formulaire. 

Dès que vous aurez crée votre session, vous pouvez retourner sur la page de connexion et vous connectez à votre session. Vous devriez par la suite arriver sur la page principal ou vous pourrez rentré le titre, description, urgence état ou encore deadline via un calendrier.

Et enfin si vous cherchez à modifier vos identifiants, vous avez la possibilité de le faire en appuyant sur le bouton profile depuis la page principal. 

Détail de déploiement heroku : 

Concerant le déploiement, je suis partie sur Heroku.

Je vais vous précisez le process que j'ai suivi pour déployer l'application :

Prérequis : 

Un compte Heroku,
Git,
Heroku CLI,
Un fichier Profile.

Commençons par ouvrir un terminal, puis plaçons nous à la racine de notre projet comme ceci : cd mon-projet

Initialisons un dépôt git si ce n'est pas encore fait en exécutant ceci : 

git init
git add .
git commit -m "Initial commit"

Puis connectons nous via "heroku login", cela ouvrira donc une page nous demandant de confirmer la connexion.

Créons ensuite l'application sur Heroku en fessant ceci : heroku create nom-de-mon-app

Puis ajoutons Heroku comme remote git : git remote -v

Ensuite quand le code sera bon et fonctionnel, nous pouvons faire : git push heroku main 

Afin de pouvoir se connecter à la base de donnéees ajoutez les variable d'environnement en exécutant ceci : heroku config:set NOM_DE_LA_VARIABLE=valeur

Afin de tester le déploiement vous pouvez lancer l'application en local via Heroku CLI : heroku local

Ensuite faites ceci : heroku open

Et si vous rencontrez des bug n'hésitons pas à afficher les logs via heroku logs --tail

Après chaque modification de code, nous devons pas oublier à ajouter les changements via : git add . / git commit -m "Mise à jour"

Puis déployer à nouveau : git push heroku main

Afin de pouvoir se connecter à la base de donnée MySQL depuis un terminal rentrez ceci : mysql -u duf8ajo281q0s9sz -p -h d1kb8x1fu8rhcnej.cbetxkdyhwsb.us-east-1.rds.amazonaws.com -P 3306 zd4x5h5nwuwpmh8y
Puis rentrez le mot de passe suivant : ohx9oat6wc6x8xad


