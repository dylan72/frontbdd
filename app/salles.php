<?php include '../assets/menu.html'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
<div class="container">

    <div class="row mt-4">
        <div class="col mt-5">
            <input type="text" id="searchInput" class="form-control" placeholder="Entrez le numéro de la salle">
        </div>
        <div class="col-auto mt-5">
            <button id="searchButton" class="btn btn-primary buttonedit">Rechercher</button>
        </div>
    </div>

    <button type="button" class="btn btn-primary buttonedit mt-4" data-toggle="modal" data-target="#modalCreerSalle">
        Ajouter une salle
    </button>

    <div class="modal fade" id="modalCreerSalle" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Nouvelle salle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCreerSalle">
                        <div class="form-group">
                            <label for="numeroSalle"><b>Numéro de salle</b></label>
                            <input type="text" class="form-control" id="numeroSalle" required>
                        </div>
                        <div class="form-group">
                            <label for="nomSalle"><b>Nom de la salle</b></label>
                            <input type="text" class="form-control" id="nomSalle" required>
                        </div>
                        <div class="form-group">
                            <label for="batiment"><b>Bâtiment</b></label>
                            <input type="text" class="form-control" id="batiment" required>
                        </div>
                        <div class="form-group">
                            <label for="capacite"><b>Capacité</b></label>
                            <input type="number" class="form-control" id="capacite" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="disponible">
                            <label class="form-check-label" for="disponible"><b>Disponible</b></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" onclick="ajouterSalle()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModifierSalle" tabindex="-1" role="dialog" aria-labelledby="modalModifierLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierLabel">Modifier la salle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formModifierSalle">
                        <input type="hidden" id="editNumeroSalle" value="">
                        <div class="form-group">
                            <label for="editNomSalle"><b>Nom de la salle</b></label>
                            <input type="text" class="form-control" id="editNomSalle" required>
                        </div>
                        <div class="form-group">
                            <label for="editBatiment"><b>Bâtiment</b></label>
                            <input type="text" class="form-control" id="editBatiment" required>
                        </div>
                        <div class="form-group">
                            <label for="editCapacite"><b>Capacité</b></label>
                            <input type="number" class="form-control" id="editCapacite" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" onclick="soumettreModification()">Enregistrer les changements</button>
                </div>
            </div>
        </div>
    </div>

    <div id="sallesContainer" class="mt-3 row"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSalleNumero = null;

    const sallesContainer = document.getElementById('sallesContainer');

    const chargerSalles = () => {
        fetch('http://localhost:9000/salles/')
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data.salles)) {
                    throw new TypeError("Les données reçues ne contiennent pas un tableau 'salles'");
                }
                sallesContainer.innerHTML = '';
                if (data.salles.length === 0) {
                    sallesContainer.innerHTML = '<p class="text-center">Aucune salle enregistrée.</p>';
                    return;
                }
                data.salles.forEach(salle => {
                    const salleElement = document.createElement('div');
                    salleElement.classList.add('col-md-4');
                    salleElement.innerHTML = `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <p class="card-text"><b>Numéro de salle :</b> ${salle.numeroSalle}</p>
                                <p class="card-text"><b>Nom de la salle:</b> ${salle.nomSalle}</p>
                                <p class="card-text"><b>Bâtiment:</b> ${salle.batiment}</p>
                                <p class="card-text"><b>Capacité:</b> ${salle.capacite}</p>
                                <p class="card-text"><b>Disponible:</b> ${salle.disponible ? "Oui" : "Non"}</p>
                                <button class="btn btn-sm btn-outline-secondary" onclick='modifierSalle(${JSON.stringify(salle)})'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="toggleDisponibilite(${salle.numeroSalle}, ${!salle.disponible})">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmerSuppression(${salle.numeroSalle}, '${salle.batiment}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    sallesContainer.appendChild(salleElement);
                });
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données:", error);
                sallesContainer.innerHTML = `<p>Erreur lors du chargement des données: ${error.message}</p>`;
            });
    };

    window.ajouterSalle = function() {
        var numeroSalle = document.getElementById('numeroSalle').value;
        var nomSalle = document.getElementById('nomSalle').value;
        var batiment = document.getElementById('batiment').value;
        var capacite = document.getElementById('capacite').value;
        var disponible = document.getElementById('disponible').checked;

        fetch('http://localhost:9000/salles/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ numeroSalle: numeroSalle, nomSalle: nomSalle, batiment: batiment, capacite: capacite, disponible: disponible })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur de réseau ou du serveur');
            }
            return response.json();
        })
        .then(() => {
            $('#modalCreerSalle').modal('hide');
            chargerSalles();
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout de la salle:', error);
            alert('Erreur lors de l\'ajout de la salle: ' + error.message);
        });
    };

    window.modifierSalle = function(salle) {
        currentSalleNumero = salle.numeroSalle;
        document.getElementById('editNomSalle').value = salle.nomSalle;
        document.getElementById('editBatiment').value = salle.batiment;
        document.getElementById('editCapacite').value = salle.capacite;

        $('#modalModifierSalle').modal('show');
    };

    window.toggleDisponibilite = function(numeroSalle, nouvelleDisponibilite) {
        const messageConfirmation = nouvelleDisponibilite
            ? "Souhaitez-vous rendre cette salle disponible ?"
            : "Souhaitez-vous rendre cette salle indisponible ?";

        if (confirm(messageConfirmation)) {
            fetch(`http://localhost:9000/salles/dispo`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    numeroSalle: numeroSalle,
                    disponible: nouvelleDisponibilite
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de réseau ou du serveur');
                }
                chargerSalles();
                alert(nouvelleDisponibilite ? "La salle est maintenant disponible." : "La salle est maintenant indisponible.");
            })
            .catch(error => {
                console.error('Erreur lors de la modification de la disponibilité:', error);
                alert('Erreur lors de la modification de la disponibilité: ' + error.message);
            });
        }
    };

    window.soumettreModification = function() {
        var nomSalle = document.getElementById('editNomSalle').value;
        var batiment = document.getElementById('editBatiment').value;
        var capacite = parseInt(document.getElementById('editCapacite').value);

        fetch(`http://localhost:9000/salles/${currentSalleNumero}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                numeroSalle: currentSalleNumero,
                nomSalle,
                batiment,
                capacite
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur de réseau ou du serveur');
            }
            $('#modalModifierSalle').modal('hide');
            chargerSalles();
        })
        .catch(error => {
            console.error('Erreur lors de la modification de la salle:', error);
            alert('Erreur lors de la modification de la salle: ' + error.message);
        });
    };

    document.getElementById('searchButton').addEventListener('click', function() {
        var numeroSalle = document.getElementById('searchInput').value;
        if (numeroSalle) {
            fetch(`http://localhost:9000/salles/byNum/${numeroSalle}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Aucun résultat trouvé.');
                }
                return response.json();
            })
            .then(salle => {
                if (!salle || Object.keys(salle).length === 0) {
                    sallesContainer.innerHTML = `<p class="text-center">Pas de résultat pour le numéro de salle ${numeroSalle}.</p>`;
                    return;
                }
                sallesContainer.innerHTML = '';
                const salleElement = document.createElement('div');
                salleElement.classList.add('col-md-12');
                salleElement.innerHTML = `
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <p class="card-text"><b>Numéro de salle :</b> ${salle.numeroSalle}</p>
                            <p class="card-text"><b>Nom de la salle:</b> ${salle.nomSalle}</p>
                            <p class="card-text"><b>Bâtiment:</b> ${salle.batiment}</p>
                            <p class="card-text"><b>Capacité:</b> ${salle.capacite}</p>
                            <p class="card-text"><b>Disponible:</b> ${salle.disponible ? "Oui" : "Non"}</p>
                            <button class="btn btn-sm btn-outline-secondary" onclick='modifierSalle(${JSON.stringify(salle)})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="toggleDisponibilite(${salle.numeroSalle}, ${!salle.disponible})">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="confirmerSuppression(${salle.numeroSalle}, '${salle.batiment}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                sallesContainer.appendChild(salleElement);
            })
            .catch(error => {
                console.error('Erreur lors de la recherche de la salle:', error);
                sallesContainer.innerHTML = `<p>${error.message}</p>`;
            });
        } else {
            alert("Veuillez entrer un numéro de salle valide.");
        }
    });

    window.confirmerSuppression = function(numeroSalle, batiment) {
        if (confirm(`Voulez-vous vraiment supprimer la salle numéro ${numeroSalle} du bâtiment '${batiment}' ?`)) {
            fetch(`http://localhost:9000/salles/numeroSalle=${numeroSalle}/batiment=${batiment}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de réseau ou du serveur lors de la suppression');
                }
                chargerSalles();
            })
            .catch(error => {
                console.error('Erreur lors de la suppression de la salle:', error);
            });
        }
    };

    chargerSalles();
});
</script>
</body>
</html>
