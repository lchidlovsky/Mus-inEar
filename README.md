# Mus'inEar

Application Web de musique, codée essentiellement en PHP, réalisée pour un projet scolaire d'informatique.

## Interfaces :

- Recherche d'albums et de groupes de musique
- Détails artiste
- Détails album
- Compte
- Page de favoris
- Création d'album

## Mise en place

### Prérequis

Pour pouvoir lancer l'application, il vous est nécessaire d'avoir `php v1.8.1.2` sur votre machine.

### Initialisation :

Pour créer la base de données nécessaire au bon fonctionnement de l'application, le fichier `creation_BDD.sh` vous sera bien utile. Vous pouvez l'exécuter à l'aide de la commande suivante :

> sh creation_BDD.sh 


### Accès au site :

De même, le fichier `lancement.sh` vous sera utile aussi puisqu'il vous permettra de lancer l'application, à l'aide de la commande suivante :

> sh lancement.sh

Le site web sera alors accessible à l'adresse [http://127.0.0.1:5000](http://127.0.0.1:5000).


## Fonctionnalités de l'application

### S'inscrire / se connecter

Pour vous inscrire ou  vous connecter à l'application, il vous suffit de cliquer sur l'icône de profil en haut à droite de la page d'accueil. Cela vous redirigera vers la page de connexion. En bas de cette page, vous trouverez un lien menant vers la page d'inscription si nécessaire.

### Faire une recherche

En haut à droite de la page d'accueil se trouve une barre de recherche. Insérez des mots clés dedans et appuyez sur la touche _Entrée_ de votre clavier afin d'effectuer une recherche sur les albums et les artistes présents dans l'application.

Cliquer sur le logo de l'application vous ramenera à la page d'accueil, avec aucun mot clé.

### Suivre un artiste

Une fois connecté, de multiples possibilités s'offrent à vous, comme le fait de suivre un artiste. Pour cela, il vous suffit de vous rendre sur sa page de profil. Vous y verrez sa biographie, sa discographie et un cœur. Cliquer sur ce coeur vous permet de suivre l'artiste.
La liste complète des artistes que vous suivez est présente dans la page des favoris.

### Mettre un album en favoris

Pour mettre un album en favoris, il vous suffit de vous rendre sur la page détaillant cet album et de cliquer sur le coeur, à gauche. Vous pourrez ainsi accéder rapidement à tous vos albums mis en favoris puisqu'ils sont présents dans la page des favoris.

### Ajouter un titre à sa playlist

De même que pour les artistes et les albums, vous pouvez intéragir avec les chansons des albums en les ajoutant à votre playlist personnelle. Pour insérer une musique dans votre playlist, il vous suffit de vous rendre sur la page d'un album, et de cliquer sur le "+" situé à droite du titre de la chanson souhaitée. Vous pourrez retrouver toutes ses chansons sur la page _Ma Playlist_.

### Fermer son compte

Si vous êtes connecté à l'application, il vous est possible de supprimer votre compte. Pour cela, rendez-vous sur la page _Mes informations_ et descendez tout en bas. Vous y trouverez un bouton _Fermer mon compte_, mais réflechissez à deux fois avant de cliquer dessus car **cette action est décisive et irréversible**. Vos données ne sont plus récupérables une fois votre compte fermé.

### Modifier sa biographie

Vous connecter à l'application vous offre des fonctionnalités différentes selon si votre compte est un compte artiste ou non. Vous pouvez savoir si vous êtes connecté avec un compte artiste en consultant la page d'accueil : l'icône de profil, en haut à droite, devrait être différente de celle visible lorsque vous n'est pas connecté.

Si vous êtes connecté avec un compte artiste, vous disposez d'une biographie. Pour l'éditer, il vous suffit de vous rendre sur la page _Mes informations_ et de modifier le texte présent, avant de cliquer sur le bouton _Enregistrer_.

### Créer et supprimer un album

Si vous êtes connecté avec un compte artiste, vous êtes élligible à la création d'album. Pour cela, rendez vous sur la page _Mes informations_ et descendez jusqu'en bas où se trouve le bouton _Créez un nouvel album_. Cliquer dessus vous redirige vers la page depuis laquelle vous pourrez insérer un nouvel album dans l'application.

Supprimer un album est faisaible depuis la page _Mes informations_. Vos albums y sont listés, en dessous de votre biographie, et cliquer sur le bouton _Supprimer_ en dessous de l'album souhaité le retirera de l'application, sans retour en arrière possible.
