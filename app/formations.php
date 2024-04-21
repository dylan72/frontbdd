<?php include '../assets/menu.html'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
<div class="container">
    <button type="button" class="btn btn-primary buttonedit mt-4" data-toggle="modal" data-target="#modalCreerFormation">
        Créer une formation
    </button>

    <!-- Création d'une formation -->
    <div class="modal fade" id="modalCreerFormation" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Nouvelle formation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCreerFormation">
                        <div class="form-group">
                            <label for="libelle">Libellé</label>
                            <input type="text" class="form-control" id="libelle" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" required>
                        </div>
                        <div class="form-group">
                            <label for="prix">Prix</label>
                            <input type="number" class="form-control" id="prix" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" id="btnCreerFormation">Créer la formation</button>
                </div>
            </div>
        </div>
    </div>

    <div id="formationsContainer" class="mt-3 row"></div>

    <div id="noFormationMessage" class="mt-3 text-center" style="display: none;">
        <p>Pas de formations disponibles.</p>
    </div>

    <div class="modal fade" id="modalModifierFormation" tabindex="-1" role="dialog" aria-labelledby="modalModifierLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierLabel">Modifier la formation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formModifierFormation">
                        <input type="hidden" id="idFormationModifier">
                        <div class="form-group">
                            <label for="idFormateur">Formateur</label>
                            <select class="form-control" id="idFormateur">
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" id="btnModifierFormation">Enregistrer les modifications</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formationsContainer = document.getElementById('formationsContainer');
    const noFormationMessage = document.getElementById('noFormationMessage');

    const chargerFormations = () => {
        fetch('http://localhost:9000/formations/')
            .then(response => response.json())
            .then(data => {
                formationsContainer.innerHTML = '';
                if(data.length === 0) {
                    noFormationMessage.style.display = 'block';
                } else {
                    noFormationMessage.style.display = 'none';
                    data.forEach(formation => {
                        const cardElement = document.createElement('div');
                        cardElement.classList.add('col-md-4');
                        cardElement.innerHTML = `
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">${formation.libelle}</h5>
                                    <p class="card-text">${formation.description}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Prix: ${formation.prix}€</small>
                                        <div>

                                             <button class="btn btn-sm btn-outline-secondary btnModifier" data-id="${formation.id}">
                                                 <i class="fas fa-edit"></i>
                                             </button>

                                             <button class="btn btn-sm btn-danger btnSupprimer" data-id="${formation.id}">
                                                 <i class="fas fa-trash"></i>
                                             </button>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        formationsContainer.appendChild(cardElement);
                    });
                    attachEventListeners();
                }
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données:", error);
                formationsContainer.innerHTML = `<p>Erreur lors du chargement des données.</p>`;
            });
    };

    const chargerFormateurs = () => {
        fetch('http://localhost:9000/utilisateurs/')
            .then(response => response.json())
            .then(utilisateurs => {
                const formateurs = utilisateurs.filter(user => user.formateur);
                const selectElement = document.getElementById('idFormateur');
                selectElement.innerHTML = '';
                formateurs.forEach(formateur => {
                    const option = document.createElement('option');
                    option.value = formateur.id;
                    option.textContent = `${formateur.prenom} ${formateur.nom}`;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors du chargement des formateurs:', error));
    };

    const supprimerFormation = (formationId) => {
        fetch(`http://localhost:9000/formations/${formationId}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la suppression de la formation');
            }
            chargerFormations();
        })
        .catch(error => console.error('Erreur lors de la suppression de la formation:', error));
    };

    const attachEventListeners = () => {
        document.querySelectorAll('.btnModifier').forEach(button => {
            button.addEventListener('click', function() {
                const formationId = this.getAttribute('data-id');
                $('#modalModifierFormation').modal('show');
                document.getElementById('idFormationModifier').value = formationId;
            });
        });

        document.querySelectorAll('.btnSupprimer').forEach(button => {
            button.addEventListener('click', function() {
                const formationId = this.getAttribute('data-id');
                if(confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
                    supprimerFormation(formationId);
                }
            });
        });
    };

    document.getElementById('btnCreerFormation').addEventListener('click', () => {
        const formationInfo = {
            libelle: document.getElementById('libelle').value,
            description: document.getElementById('description').value,
            prix: document.getElementById('prix').value
        };

        fetch('http://localhost:9000/formations/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formationInfo)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur de réseau ou du serveur');
            }
            $('#modalCreerFormation').modal('hide');
            chargerFormations();
        })
        .catch(error => {
            console.error('Erreur lors de la création de la formation:', error);
        });
    });

    chargerFormations();
    chargerFormateurs();
});
</script>
</body>
</html>