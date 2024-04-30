<?php include '../assets/menu.html'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
<div class="container">

    <button type="button" class="buttonedit btn btn-primary mt-4" data-toggle="modal" data-target="#modalCreerUtilisateur">
        Ajouter un utilisateur
    </button>

    <div class="modal fade" id="modalCreerUtilisateur" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Nouvel utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCreerUtilisateur">
                        <div class="form-group">
                            <label for="nom"><b>Nom</b></label>
                            <input type="text" class="form-control" id="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom"><b>Prénom</b></label>
                            <input type="text" class="form-control" id="prenom" required>
                        </div>
                        <div class="form-group">
                             <label for="email"><b>Email</b></label>
                             <input type="email" class="form-control" id="email" required>
                         </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" onclick="ajouterUtilisateur()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <div id="utilisateursContainer" class="mt-3 row"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const utilisateursContainer = document.getElementById('utilisateursContainer');

    const chargerUtilisateurs = () => {
        fetch('http://localhost:9000/utilisateurs/')
            .then(response => response.json())
            .then(data => {
                utilisateursContainer.innerHTML = '';
                data.forEach(utilisateur => {
                    const utilisateurElement = document.createElement('div');
                    utilisateurElement.classList.add('col-md-4');
                    const formateurIconClass = utilisateur.formateur ? "fas fa-chalkboard-teacher text-success" : "fas fa-chalkboard-teacher";
                    utilisateurElement.innerHTML = `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <p class="card-text"><b>Nom :</b> ${utilisateur.nom}</p>
                                <p class="card-text"><b>Prénom:</b> ${utilisateur.prenom}</p>
                                <p class="card-text"><b>Email:</b> ${utilisateur.email}</p>
                                <button class="btn btn-sm btn-outline-secondary" onclick="toggleFormateur(${utilisateur.id}, ${utilisateur.formateur})">
                                    <i class="${formateurIconClass}"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmerSuppression('${utilisateur.nom}', '${utilisateur.prenom}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    utilisateursContainer.appendChild(utilisateurElement);
                });
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données:", error);
                utilisateursContainer.innerHTML = `<p>Aucun utilisateur existant</p>`;
            });
    };

    window.toggleFormateur = function(id, formateur) {
        const message = formateur ? "Souhaitez-vous retirer le rôle de formateur à cet utilisateur ?" : "Souhaitez-vous attribuer le rôle de formateur à cet utilisateur ?";
        if(confirm(message)) {
            fetch(`http://localhost:9000/utilisateurs/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, formateur: !formateur })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de réseau ou du serveur');
                }
                chargerUtilisateurs();
            })
            .catch(error => {
                console.error('Erreur lors de la modification du rôle:', error);
            });
        }
    };

    window.ajouterUtilisateur = function() {
        var nom = document.getElementById('nom').value;
        var prenom = document.getElementById('prenom').value;
        var email = document.getElementById('email').value;

        if (!nom || !prenom || !email) {
            alert('Tous les champs sont requis!');
            return;
        }

        fetch('http://localhost:9000/utilisateurs/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ nom: nom, prenom: prenom, email: email })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur de réseau ou du serveur');
            }
            return response.json();
        })
        .then(data => {
            $('#modalCreerUtilisateur').modal('hide');
            chargerUtilisateurs();
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout de l\'utilisateur:', error);
            alert('Erreur lors de l\'ajout de l\'utilisateur: ' + error.message);
        });
    };


    window.confirmerSuppression = function(nom, prenom) {
        if(confirm(`Voulez-vous vraiment supprimer l'utilisateur ${nom} ${prenom} ?`)) {
            fetch(`http://localhost:9000/utilisateurs/nom=${nom}&prenom=${prenom}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de réseau ou du serveur lors de la suppression');
                }
                chargerUtilisateurs();
            })
            .catch(error => {
                console.error('Erreur lors de la suppression de l\'utilisateur:', error);
            });
        }
    };

    chargerUtilisateurs();
});
</script>
</body>
</html>
