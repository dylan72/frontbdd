## Instructions d'Installation

### Prérequis
Pour exécuter ce projet, un serveur local est nécessaire. Nous recommandons l'installation de XAMPP. Une fois installé, placez l'ensemble du projet à la racine du serveur.

### Structure du Site
Le site comprend cinq pages principales, chacune avec des fonctionnalités spécifiques :

#### Page 1 : Accueil
- Présentation succincte des fonctionnalités du front-end.

#### Page 2 : Gestion des Utilisateurs
- **Lister les utilisateurs** : Accès à tous les utilisateurs enregistrés (`GET all`).
- **Ajouter un utilisateur** : Création de nouveaux utilisateurs (`POST`).
- **Modifier un utilisateur** : Attribution du rôle de formateur (`PUT`).
- **Supprimer un formateur** : Retrait du rôle et suppression de l'utilisateur (`DELETE`).

#### Page 3 : Gestion des Salles
- **Lister les salles** : Affichage de toutes les salles disponibles (`GET all`).
- **Rechercher une salle** : Recherche par numéro (`GET by number`).
- **Ajouter une salle** : Enregistrement d'une nouvelle salle (`POST`).
- **Modifier une salle** : Changement des informations ou disponibilité (`PUT`).
- **Supprimer une salle** : Retrait de la salle du système (`DELETE`).

#### Page 4 : Gestion des Formations
- **Lister les formations** : Visualisation de toutes les formations (`GET all`).
- **Ajouter une formation** : Création d'une nouvelle formation (`POST`).
- **Associer un formateur à une formation** : Attribution du rôle de formateur (`PUT IsFormateur`).
- **Supprimer une formation** : Élimination de la formation (`DELETE`).

**Note** : L'attribution du rôle de formateur nécessite une modification préalable via le menu utilisateur. L'envoi de cette requête peut nécessiter plusieurs tentatives en cas d'erreur car il peut y'avoir des bugs.

#### Page 5 : Gestion des Séances
- **Lister les séances** : Affichage de toutes les séances programmées (`GET all`).
- **Ajouter une séance** : Planification d'une nouvelle séance (`POST`).
- **Modifier une séance** : Modification des détails de la séance (`PUT`).
- **Supprimer une séance** : Annulation de la séance programmée (`DELETE`).

### Intégration avec RabbitMQ
L'utilisation de RabbitMQ permet une gestion automatique des dépendances entre les données. Par exemple, la suppression d'une formation ou d'une salle entraîne la suppression des séances associées. De même, la suppression d'un utilisateur ayant le rôle de formateur entraîne la suppression des formations liées à cet utilisateur.